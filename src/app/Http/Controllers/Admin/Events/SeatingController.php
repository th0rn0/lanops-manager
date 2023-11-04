<?php

namespace App\Http\Controllers\Admin\Events;

use DB;
use Session;
use Storage;

use App\User;
use App\Event;
use App\EventTicket;
use App\EventSeating;
use App\EventSeatingPlan;
use app\EventSeatingPlanSeat;
use App\EventParticipant;
use App\EventParticipantType;

use App\Http\Requests;
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
            ->withSeats($seatingPlan->seats()->where('status', 'ACTIVE')->paginate(15, ['*'], 'se'));
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
            "rows"      => "required|integer|max:26",
            'image'     => 'image',
        ];
        $messages = [
            'name.required'     => 'Name is required',
            'columns.required'  => 'Columns is required',
            'columns.integer'   => 'Columns must be a number',
            'rows.required'     => 'Rows is required',
            'rows.max'          => 'Max. 26 Rows are allowed',
            'rows.integer'      => 'Rows must be a number',
            'image.image'       => 'Seating image must be a image',
        ];
        $this->validate($request, $rules, $messages);

        $seatingPlan                = new EventSeatingPlan();
        $seatingPlan->event_id      = $event->id;
        $seatingPlan->name          = $request->name;
        $seatingPlan->name_short    = @$request->name_short;

        $alphabet = range('A', 'Z');
        for ($i = 0; $i < $request->rows; $i++) {
            $seatingHeaders[] = $alphabet[$i];
        }
        $seatingPlan->headers  = implode(',', $seatingHeaders);
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
            "rows"      => "integer|max:26",
            'image'     => 'image',
            'status'    => 'in:draft,preview,published',
            'locked'    => 'boolean',
            'name'      => 'filled',
        ];
        $messages = [
            'columns.integer'   => 'Columns must be a number',
            'rows.integer'      => 'Rows must be a number',
            'rows.max'          => 'Max. 26 Rows are allowed',
            'image.image'       => 'Seating image must be a image',
            'status.in'         => 'Status must be draft or published',
            'locked.boolean'    => 'Locked must be a boolean',
            'name.filled'       => 'Name cannout be empty',
        ];
        $this->validate($request, $rules, $messages);

        $seatingPlanName = "";
        if (isset($request->name)) {
            $seatingPlan->name  = $request->name;
            $seatingPlanName    = $request->name;
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
                for ($i = 0; $i < $request->rows; $i++) {
                    $seatingHeaders[]   = $alphabet[$i];
                }
                $seatingPlan->headers   = implode(',', $seatingHeaders);
                $seatingPlan->columns   = $request->columns;
                $seatingPlan->rows      = $request->rows;
            }
        }

        if ($request->file('image') !== null) {
            
            if (isset($seatingPlan->image_path) && $seatingPlan->image_path != "")
            {
                Storage::delete($seatingPlan->image_path);
            }

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
            Session::flash('alert-danger', 'Could not store or update Seating Plan ' . $seatingPlanName . '!');
            return Redirect::back();
        }

        Session::flash('alert-success', 'Successfully updated Seating Plan ' . $seatingPlanName . '!');
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
        $seatPlanName = $seatingPlan->getName();
        
        if (!$seatingPlan->delete()) {
            Session::flash('alert-danger', 'Could not delete Seating Plan ' . $seatPlanName . '!');
            return Redirect::back();
        }

        Session::flash('alert-success', 'Successfully deleted Seating Plan ' . $seatPlanName . '!');
        return Redirect::to('/admin/events/' . $event->slug . '/seating');
    }

    /**
     * Seat Participant
     * @param  Event            $event
     * @param  EventSeatingPlan $seatingPlan
     * @param  Request          $request
     * @return Redirect
     */
    public function storeSeat(Event $event, EventSeatingPlan $seatingPlan, Request $request)
    {

        $rules = [
            "seat_column"               => "required|integer",
            "seat_row"                  => "required|integer|max:26",
            'seat_status_select_modal'  => 'required|in:ACTIVE,INACTIVE',
        ];
        $messages = [
            'seat_column.required'              => 'A column is required',
            'seat_column.integer'               => 'Columns must be a number',
            'seat_row.required'                 => 'A row is required',
            'seat_row.integer'                  => 'Rows must be a number',
            'seat_row.max'                      => 'Max. 26 Rows are allowed',
            'seat_status_select_modal.required' => 'A seat status is required',
            'seat_status_select_modal.in'       => 'Status must be active or inactive',
        ];

        $this->validate($request, $rules, $messages);

        if (
            $request->seat_column <= 0 ||
            $request->seat_column > $seatingPlan->columns ||
            $request->seat_row <= 0 ||
            $request->seat_row > $seatingPlan->rows
        ) {
            //If this happens, there probably is/are some major bug(s) elsewhere and possible data inconsistency
            Session::flash('alert-danger', 'Invalid seat selection!');
            return Redirect::back();
        }

        
        //Make sure that any kind of status is given from view
        if (!isset($request->seat_status_select_modal) || trim($request->seat_status_select_modal) == '') {
            Session::flash('alert-danger', 'A status has to be selected!');
            return Redirect::back();
        }

        //Check if ticket is even seatable
        if (isset($request->participant_select_modal) && trim($request->participant_select_modal) != '') {
            $clauses = ['id' => $request->participant_select_modal];
            $participant = EventParticipant::where($clauses)->first();
            if (($participant->ticket && !$participant->ticket->seatable)) {
                Session::flash('alert-danger', 'Ticket is not eligible for a seat!');
                return Redirect::back();
            }
        }

        //Check if the participant has already a seat and remove it if necessary
        //There can only be one!!!
        if (isset($request->participant_id_modal) && trim($request->participant_id_modal) != '') {
            $clauses = ['event_participant_id' => $request->participant_id_modal];
            $previousSeat = EventSeating::where($clauses)->first();
            if ($previousSeat != null && $previousSeat->status == 'ACTIVE' && strtoupper($request->seat_status_select_modal) == 'ACTIVE') {
                $previousSeat->delete();
            }
        }

        $clauses = [
            'event_participant_id' => $request->participant_select_modal
        ];
        $previousSeat = EventSeating::where($clauses)->first();        
        if ($previousSeat != null && $previousSeat->status == 'ACTIVE' &&  strtoupper($request->seat_status_select_modal) == 'ACTIVE') {
            $previousSeat->delete();
        }

        //An occupied seat has to be cleared of itts seating first, before making any new changes
        $clauses = [
            'column'                => $request->seat_column, 
            'row'                   => $request->seat_row, 
            'event_seating_plan_id' => $seatingPlan->id
        ];
        $seat = EventSeating::where($clauses)->first();
                
        if ($seat != null) {
            $seatName = $seat->getName();
            Session::flash('alert-danger', 'Seat ' . $seatName . ' is still occupied. Please try again or delete the seating first!');
            return Redirect::back();
        }
        
        //If status is set to active, the seat has to be paired with an event participant
        if ((!isset($request->participant_select_modal) && trim($request->participant_select_modal) == '')
            &&  strtoupper($request->seat_status_select_modal) == 'ACTIVE'
        ) {
            Session::flash('alert-danger', 'Seat can not be active and not have an event participant!');
            return Redirect::back();
        }

        //If a participant is selected for seating, status can't be inactive
        if ((isset($request->participant_select_modal) && trim($request->participant_select_modal) != '')
            &&  strtoupper($request->seat_status_select_modal) == 'INACTIVE'
        ) {
            Session::flash('alert-danger', 'Seat for event participants can not be inactive!');
            return Redirect::back();
        }

        $newSeat                            = new EventSeating();
        $newSeat->column                    = $request->seat_column;
        $newSeat->row                       = $request->seat_row;
        $newSeat->event_participant_id      = $request->participant_select_modal;
        $newSeat->event_seating_plan_id     = $seatingPlan->id;
        $newSeat->status                    = $request->seat_status_select_modal;

        $seatName = $newSeat->getName();
        $seatPlanName = $seatingPlan->getName();
        
        if (!$newSeat->save()) {
            Session::flash('alert-danger', 'Could not store or update seat ' . $seatName . ' of seating plan ' . $seatPlanName . '!');
            return Redirect::back();
        }

        Session::flash('alert-success', 'Seat ' . $seatName . ' of plan ' . $seatPlanName . ' successfully updated!');
        return Redirect::back();
    }

    /**
     * Remove Participant Seating
     * @param  Event            $event
     * @param  EventSeatingPlan $seatingPlan
     * @param  Request          $request
     * @return Redirect
     */
    public function destroySeat(Event $event, EventSeatingPlan $seatingPlan, Request $request)
    {
        
        $rules = [
            'seat_column_delete'    => 'required',
            'seat_row_delete'       => 'required',
        ];
        $messages = [
            'seat_column_delete|required'   => 'A seat column is required',
            'seat_row_delete|required'      => 'A seat row is required',            
        ];

        $this->validate($request, $rules, $messages);        
        
        $clauses = [
            'column'    => $request->seat_column_delete, 
            'row'       => $request->seat_row_delete
        ];
        if (!$seat = $seatingPlan->seats()->where($clauses)->first()) {
            Session::flash('alert-danger', 'Could not find seat!');
            return Redirect::back();
        }

        $seatName = $seat->getName();
        $seatPlanName = $seatingPlan->getName();

        if (!$seat->delete()) {
            Session::flash('alert-danger', 'Could not clear seat ' . $seatName . ' of seating plan ' . $seatPlanName . '!');
            return Redirect::back();
        }

        Session::flash('alert-success', 'Seat ' . $seatName . ' of plan ' . $seatPlanName . 'successfully deleted!');
        return Redirect::back();
    }
}
