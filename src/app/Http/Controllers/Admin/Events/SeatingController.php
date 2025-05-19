<?php

namespace App\Http\Controllers\Admin\Events;

use Session;
use Storage;

use App\Models\Event;
use App\Models\EventSeating;
use App\Models\EventSeatingPlan;
use App\Models\EventParticipant;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class SeatingController extends Controller
{
    /**
     * Show Seating Index Page
     * @param  Event  $event
     * @return View
     */
    public function index(Event $event)
    {
        return view('admin.events.seating.index')
            ->withEvent($event)
            ->withSeatingPlans($event->seatingPlans()->paginate(10));
    }

    /**
     * Show Seating Page
     * @param  Event            $event
     * @param  EventSeatingPlan $seatingPlan
     * @return View
     */
    public function show(Event $event, EventSeatingPlan $seatingPlan)
    {
        return view('admin.events.seating.show')
            ->withEvent($event)
            ->withSeatingPlan($seatingPlan)
            ->withSeats($seatingPlan->seats()->where('event_participant_id', '!=', null)->paginate(15, ['*'], 'se'));
    }

    /**
     * Add Seating Plan to Database
     * @param  Event   $event
     * @param  Request $request
     * @return Redirect
     */
    public function store(Event $event, Request $request)
    {
        $rules = [
            "name"      => "required",
            "columns"   => "required|integer",
            "rows"      => "required|integer",
            'image'     => 'image',
        ];
        $messages = [
            'name.required'     => 'Name is required',
            'columns.required'  => 'Columns is required',
            'columns.integer'   => 'Columns must be a number',
            'rows.required'     => 'Rows is required',
            'rows.integer'      => 'Rows must be a number',
            'image.image'       => 'Seating image must be a image',
        ];
        $this->validate($request, $rules, $messages);

        $seatingPlan                = new EventSeatingPlan();
        $seatingPlan->event_id      = $event->id;
        $seatingPlan->name          = $request->name;
        $seatingPlan->name_short    = @$request->name_short;

        $seatingPlan->columns  = $request->columns;
        $seatingPlan->rows     = $request->rows;

        if ($request->file('image') !== null) {
            $seatingPlan->image_path = str_replace(
                'public/',
                '/storage/',
                Storage::put(
                    'public/images/events/' . $event->slug . '/seating/' . $seatingPlan->slug,
                    $request->file('image')
                )
            );
        }

        if (!$seatingPlan->save()) {
            Session::flash('alert-danger', 'Could not save Seating Plan!');
            return Redirect::back();
        }

        Session::flash('alert-success', 'Successfully saved Seating Plan!');
        return Redirect::to('admin/events/' . $event->slug . '/seating/' . $seatingPlan->slug);
    }

    /**
     * Update Seating Plan
     * @param  Event            $event
     * @param  EventSeatingPlan $seatingPlan
     * @param  Request          $request
     * @return Redirect
     */
    public function update(Event $event, EventSeatingPlan $seatingPlan, Request $request)
    {
        $rules = [
            "columns"   => "integer",
            "rows"      => "integer",
            'image'     => 'image',
            'status'    => 'in:draft,preview,published',
            'locked'    => 'boolean',
            'name'      => 'filled',
        ];
        $messages = [
            'columns.integer'   => 'Columns must be a number',
            'rows.integer'      => 'Rows must be a number',
            'image.image'       => 'Seating image must be a image',
            'status.in'         => 'Status must be draft or published',
            'locked.boolean'    => 'Locked must be a boolean',
            'name.filled'       => 'Name cannout be empty',
        ];
        $this->validate($request, $rules, $messages);

        if (isset($request->name)) {
            $seatingPlan->name          = $request->name;
        }

        if (isset($request->name_short)) {
            $seatingPlan->name_short        = $request->name_short;
        }

        if (isset($request->status)) {
            $seatingPlan->status        = $request->status;
        }

        $seatingPlan->locked            = ($request->locked ? true : false);

        if (isset($request->rows) || isset($request->columns)) {
            if ($seatingPlan->columns != $request->columns || $seatingPlan->rows != $request->rows) {
                $alphabet = range('A', 'Z');
                for ($i = 0; $i < $request->columns; $i++) {
                    $seatingHeaders[]   = $alphabet[$i];
                }
                $seatingPlan->headers   = implode(',', $seatingHeaders);
                $seatingPlan->columns   = $request->columns;
                $seatingPlan->rows      = $request->rows;
            }
        }

        if ($request->file('image') !== null) {
            Storage::delete($seatingPlan->image_path);
            $seatingPlan->image_path = str_replace(
                'public/',
                '/storage/',
                Storage::put(
                    'public/images/events/' . $event->slug . '/seating/' . $seatingPlan->slug,
                    $request->file('image')
                )
            );
        }

        if (!$seatingPlan->save()) {
            Session::flash('alert-danger', 'Could not update Seating Plan!');
            return Redirect::back();
        }

        Session::flash('alert-success', 'Successfully updated Seating Plan!');
        return Redirect::back();
    }

    /**
     * Delete Seating Plan
     * @param  Event            $event
     * @param  EventSeatingPlan $seatingPlan
     * @return Redirect
     */
    public function destroy(Event $event, EventSeatingPlan $seatingPlan)
    {
        if (!$seatingPlan->delete()) {
            Session::flash('alert-danger', 'Could not delete Seating Plan!');
            return Redirect::back();
        }

        Session::flash('alert-success', 'Successfully deleted Seating Plan!');
        return Redirect::to('/admin/events/' . $event->slug . '/seating');
    }

    /**
     * Seat Participant
     * @param  Event            $event
     * @param  EventSeatingPlan $seatingPlan
     * @param  Request          $request
     * @return Redirect
     */
    public function updateSeat(Event $event, EventSeatingPlan $seatingPlan, Request $request)
    {
        if (isset($request->participant_select_modal) && trim($request->participant_select_modal) != '') {
            $clauses = ['id' => $request->participant_select_modal];
            $participant = EventParticipant::where($clauses)->first();
            if (($participant->ticket && !$participant->ticket->seatable)) {
                Session::flash('alert-danger', 'Ticket is not eligible for a seat!');
                return Redirect::back();
            }
        }

        if (!$seat = EventSeating::where([
            'seat' => $request->seat_number_modal,
            'event_seating_plan_id' => $seatingPlan->id
        ])->first()) {
            Session::flash('alert-danger', 'Invalid seat selection!');
            return Redirect::back();
        }

        if ($previousSeat = EventSeating::where([
            'event_seating_plan_id' => $seatingPlan->id,
            'event_participant_id' => $request->participant_select_modal
        ])->first()) {
            $previousSeat->event_participant_id = null;
            if (!$previousSeat->save()) {
                Session::flash('alert-danger', 'Could not update Seat!');
                return Redirect::back();
            }
        }

        $seat->event_participant_id = $request->participant_select_modal;
        $seat->disabled             = false;

        if (!$seat->save()) {
            Session::flash('alert-danger', 'Could not update Seat!');
            return Redirect::back();
        }

        Session::flash('alert-success', 'Successfully updated Seat!');
        return Redirect::back();
    }

    /**
     * Clear Participant Seating
     * @param  Event            $event
     * @param  EventSeatingPlan $seatingPlan
     * @param  Request          $request
     * @return Redirect
     */
    public function clearSeat(Event $event, EventSeatingPlan $seatingPlan, Request $request)
    {
        if (!$seat = $seatingPlan->seats()->where('seat', $request->seat_number_clear)->first()) {
            Session::flash('alert-danger', 'Could not find seat!');
            return Redirect::back();
        }

        $seat->event_participant_id = null;

        if (!$seat->save()) {
            Session::flash('alert-danger', 'Could not clear seat!');
            return Redirect::back();
        }

        Session::flash('alert-success', 'Seat Updated!');
        return Redirect::back();
    }

    /**
     * Disable/Enable Participant Seating
     * @param  Event            $event
     * @param  EventSeatingPlan $seatingPlan
     * @param  Request          $request
     * @return Redirect
     */
    public function disableSeat(Event $event, EventSeatingPlan $seatingPlan, Request $request)
    {
        $rules = [
            "seat_number_disable" => "filled",
        ];
        $messages = [
            'seat_number_disable.filled' => 'You must enter a seat number',
        ];
        $this->validate($request, $rules, $messages);

        if (!$seat = $seatingPlan->seats()->where('seat', $request->seat_number_disable)->first()) {
            Session::flash('alert-danger', 'Could not disable/enable seat!');
            return Redirect::back();
        }

        if ($seat->disabled) {
            $seat->disabled = false;
        } else {
            $seat->disabled = true;
        }

        $seat->event_participant_id = null;

        if (!$seat->save()) {
            Session::flash('alert-danger', 'Could not disable/enable seat!');
            return Redirect::back();
        }
        Session::flash('alert-success', 'Seat Updated!');
        return Redirect::back();
    }
}
