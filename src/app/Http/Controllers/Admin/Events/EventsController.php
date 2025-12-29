<?php

namespace App\Http\Controllers\Admin\Events;

use Auth;
use Session;

use App\Models\Event;
use App\Models\EventTimetable;
use App\Models\EventParticipant;
use App\Models\EventTimetableData;
use App\Models\EventTicket;
use App\Models\EventSeatingPlan;
use App\Models\EventInformation;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Http;

use Spatie\WebhookServer\WebhookCall;

class EventsController extends Controller
{
    /**
     * Show Events Page Index
     * @return View
     */
    public function index()
    {
        return view('admin.events.index')
            ->withUser(Auth::user())
            ->withEvents(Event::withoutGlobalScopes()->orderBy('created_at', 'DESC')->paginate(10));
    }

    /**
     * Show Events Page
     * @param  Event  $event
     * @return View
     */
    public function show(Event $event)
    {
        return view('admin.events.show')
            ->withUser(Auth::user())
            ->withEvent($event)
            ->withParticipants($event->eventParticipants()->paginate(10, ['*'], 'ep'));
    }

    /**
     * Add Event to Database
     * @param  Request $request
     * @return Redirect
     */
    public function store(Request $request)
    {
        $rules = [
            'event_name'    => 'required|unique:events,display_name',
            'desc_short'    => 'required',
            'desc_long'     => 'required',
            'end_date'      => 'required|date_format:m/d/Y',
            'end_time'      => 'required|date_format:H:i',
            'start_date'    => 'required|date_format:m/d/Y',
            'start_time'    => 'required|date_format:H:i',
            'capacity'      => 'required|integer',
            'venue'         => 'exists:event_venues,id',
        ];
        $messages = [
            'event_name.required'       => 'Event name is required',
            'start_date.required'       => 'Start date is required',
            'start_time.required'       => 'Start date is required',
            'end_date.required'         => 'End date is required',
            'end_time.required'         => 'End date is required',
            'end_date.date_format'      => 'End Date must be m/d/Y format',
            'end_time.date_format'      => 'End Time must be H:i format',
            'start_date.date_format'    => 'Start Date must be m/d/Y format',
            'start_time.date_format'    => 'Start Time must be H:i format',
            'desc_short.required'       => 'Short Description is required',
            'desc_long.required'        => 'Long Description is required',
            'capacity.required'         => 'Capacity is required',
            'capacity.integer'          => 'Capacity must be a integer',
            'venue.exists'              => 'A venue is required'

        ];
        $this->validate($request, $rules, $messages);

        $event                      = new Event();
        $event->display_name        = $request->event_name;
        $event->nice_name           = strtolower(str_replace(' ', '-', $request->event_name));
        $event->start               = date("Y-m-d H:i:s", strtotime($request->start_date . $request->start_time));
        $event->end                 = date("Y-m-d H:i:s", strtotime($request->end_date . $request->end_time));
        $event->desc_long           = $request->desc_long;
        $event->desc_short          = $request->desc_short;
        $event->event_venue_id      = @$request->venue;
        $event->capacity            = $request->capacity;

        if (!$event->save()) {
            Session::flash('alert-danger', 'Cannot Save Event!');
            return Redirect::to('admin/events/' . $event->slug);
        }
        Session::flash('alert-success', 'Successfully saved Event!');
        return Redirect::to('admin/events/' . $event->slug);
    }
    
    /**
     * Update Event
     * @param  Event   $event
     * @param  Request $request
     * @return Redirect
     */
    public function update(Event $event, Request $request)
    {
        $rules = [
            'event_name'        => 'filled',
            'end_date'          => 'filled|date_format:m/d/Y',
            'end_time'          => 'filled|date_format:H:i',
            'start_date'        => 'filled|date_format:m/d/Y',
            'start_time'        => 'filled|date_format:H:i',
            'status'            => 'in:draft,preview,published,private',
            'capacity'          => 'filled|integer',
            'venue'             => 'exists:event_venues,id',
            'type'              => 'in:LAN,TABLETOP',
        ];
        $messages = [
            'event_name.filled'         => 'Event Name cannot be empty',
            'end_date.filled'           => 'A End Date cannot be empty',
            'end_date.date_format'      => 'A End Date must be m/d/Y format',
            'end_time.filled'           => 'A End Time cannot be empty',
            'end_time.date_format'      => 'A End Time must be H:i formate',
            'start_date.filled'         => 'A Start Date cannut be empty',
            'end_date.date_format'      => 'A Start Date must be m/d/Y format',
            'start_time.filled'         => 'A Start Time cannot be empty',
            'end_time.date_format'      => 'A Start Time must be H:i format',
            'status.in'                 => 'Status must be draft, preview, published or private',
            'capacity.filled'           => 'Capacity is required',
            'capacity.integer'          => 'Capacity must be a integer',
            'venue.exists'              => 'A venue is required',
            'type.in'                   => 'Type must be LAN or TABLETOP',

        ];
        $this->validate($request, $rules, $messages);

        if (isset($request->event_name)) {
            $event->display_name    = $request->event_name;
            $event->nice_name       = strtolower(str_replace(' ', '-', $request->event_name));
        }

        if (isset($request->start_date)) {
            $start = $request->start_date . date("H:i:s", strtotime($event->start));
            $event->start           = date("Y-m-d H:i:s", strtotime($start));
        }

        if (isset($request->start_time)) {
            $start = date("Y-m-d", strtotime($event->start)) . $request->start_time;
            $event->start           = date("Y-m-d H:i:s", strtotime($start));
        }

        if (isset($request->end_date)) {
            $end = $request->end_date . date("H:i:s", strtotime($event->end));
            $event->end             = date("Y-m-d H:i:s", strtotime($end));
        }

        if (isset($request->end_time)) {
            $end = date("Y-m-d", strtotime($event->end)) . $request->end_time;
            $event->end             = date("Y-m-d H:i:s", strtotime($end));
        }

        if (isset($request->desc_long)) {
            $event->desc_long       = $request->desc_long;
        }

        if (isset($request->desc_short)) {
            $event->desc_short      = $request->desc_short;
        }

        if (isset($request->status)) {
            $event->status          = $request->status;
        }

        if (isset($request->capacity)) {
            $event->capacity        = $request->capacity;
        }

        
        if (isset($request->type)) {
            $event->type            = $request->type;
        }

        if (!$event->save()) {
            Session::flash('alert-danger', 'Cannot update Event!');
            return Redirect::to('admin/events/' . $event->slug);
        }

        Session::flash('alert-success', 'Successfully updated Event!');
        return Redirect::to('admin/events/' . $event->slug);
    }

    /**
     * Delete Event from Database
     * @param  Event  $event
     * @return Redirect
     */
    public function destroy(Event $event)
    {
        if (!$event->eventParticipants->isEmpty()) {
            Session::flash('alert-danger', 'Cannot delete event with participants!');
            return Redirect::back();
        }

        if (!$event->delete()) {
            Session::flash('alert-danger', 'Cannot delete Event!');
            return Redirect::back();
        }

        Session::flash('alert-success', 'Successfully deleted Event!');
        return Redirect::to('admin/events/');
    }

    /**
     * Add Gift Participant
     * @param  Request $request
     * @param  Event   $event
     * @return Redirect
     */
    public function freeGift(Request $request, Event $event)
    {
        $participant                            = new EventParticipant();
        $participant->user_id                   = $request->user_id;
        $participant->event_id                  = $event->id;
        $participant->free                      = 1;
        $participant->staff_free_assigned_by    = Auth::id();
        
        if (!$participant->save()) {
            Session::flash('alert-danger', 'Could not add Gift!');
            return Redirect::to('admin/events/' . $event->slug . '/tickets');
        }

        Session::flash('alert-success', 'Successfully added Gift!');
        return Redirect::to('admin/events/' . $event->slug . '/tickets');
    }

    /**
     * Add Admin Participant
     * @param  Request $request
     * @param  Event   $event
     * @return Redirect
     */
    public function freeStaff(Request $request, Event $event)
    {
        $participant = new EventParticipant();

        $participant->user_id                = $request->user_id;
        $participant->event_id               = $event->id;
        $participant->staff                  = 1;
        $participant->staff_free_assigned_by = Auth::id();

        if (!$participant->save()) {
            Session::flash('alert-danger', 'Could not add Admin!');
            return Redirect::to('admin/events/' . $event->slug . '/tickets');
        }

        Session::flash('alert-success', 'Successfully added Admin!');
        return Redirect::to('admin/events/' . $event->slug . '/tickets');
    }

    public function linkDiscord(Request $request, Event $event)
    {
        // TODO - put this into the model
        $address = [
            $event->venue->address_1,
            $event->venue->address_2,
            $event->venue->address_street,
            $event->venue->address_city,
            $event->venue->address_postcode,
            $event->venue->address_country,
        ];
        $response = Http::withBasicAuth(
            config('app.discord_bot_user'),
            config('app.discord_bot_pass'))
        ->post(config('app.discord_bot_url') . '/events/create', [
            'name' => $event->display_name,
            'slug' => $event->slug,
            'url' => config('app.url') . '/events/' . $event->slug,
            'start' => $event->start,
            'end' => $event->end,
            'address' => implode(", ", $address)
        ]);

        if ($response->status() != 200) {
            Session::flash('alert-danger', 'Cannot Link Event! ' . $response);
            return Redirect::to('admin/events/' . $event->slug);
        }

        $event->discord_link_enabled = true;
        $event->discord_role_id = $response['role_id'];
        $event->discord_channel_id = $response['channel_id'];
        $event->discord_event_id = $response['event_id'];
        if (!$event->save()) {
            Session::flash('alert-danger', 'Cannot Link Event!');
            return Redirect::to('admin/events/' . $event->slug);
        }

        foreach($event->eventParticipants as $participant) {
                WebhookCall::create()
                ->url(config('app.discord_bot_url') . '/participants/new')
                ->payload([
                    'username' => $participant->user->steamname,
                    'discord_id' => $participant->user->discord_id,
                    'channel_id' => $event->discord_channel_id,
                    'role_id' => $event->discord_role_id,
                    'no_message' => true
                ])
                ->useSecret(config('app.discord_bot_secret'))
                ->dispatch();
        }

        Session::flash('alert-success', 'Successfully Linked Event!');
        return Redirect::to('admin/events/' . $event->slug);

    }

    public function duplicate(Request $request, Event $event)
    {
        // TODO - Put this into the model
        $randomNameSuffix = substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil(5/strlen($x)) )),1,5);
        $duplicatedEvent = new Event();
        $duplicatedEvent->display_name        = $event->display_name . " Copy ". $randomNameSuffix;
        $duplicatedEvent->nice_name           = strtolower(str_replace(' ', '-', $event->display_name . " Copy " . $randomNameSuffix));
        $duplicatedEvent->start               = $event->start;
        $duplicatedEvent->end                 = $event->end;
        $duplicatedEvent->desc_long           = $event->desc_long;
        $duplicatedEvent->desc_short          = $event->desc_short;
        $duplicatedEvent->event_venue_id      = @$event->event_venue_id;
        $duplicatedEvent->capacity            = $event->capacity;

        if (!$duplicatedEvent->save()) {
            Session::flash('alert-danger', 'Cannot save Event!');
            return Redirect::back();
        }

        foreach ($event->tickets as $ticket) {
            $duplicatedTicket             = new EventTicket();
            $duplicatedTicket->event_id   = $duplicatedEvent->id;
            $duplicatedTicket->name       = $ticket->name;
            $duplicatedTicket->type       = $ticket->type;
            $duplicatedTicket->price      = $ticket->price;
            $duplicatedTicket->seatable   = $ticket->seatable;
            $duplicatedTicket->sale_start = $ticket->sale_start;
            $duplicatedTicket->sale_end   = $ticket->sale_end;
            $duplicatedTicket->quantity   = $ticket->quantity;
    
            if (!$duplicatedTicket->save()) {
                Session::flash('alert-danger', 'Cannot save Ticket');
                Redirect::back();
            }
        }

        foreach ($event->seatingPlans as $seatingPlan) {
            $duplicatedSeatingPlan              = new EventSeatingPlan();
            $duplicatedSeatingPlan->event_id    = $duplicatedEvent->id;
            $duplicatedSeatingPlan->name        = $seatingPlan->name;
            $duplicatedSeatingPlan->name_short  = $seatingPlan->name_short;
            $duplicatedSeatingPlan->columns     = $seatingPlan->columns;
            $duplicatedSeatingPlan->rows        = $seatingPlan->rows;

            if (!$duplicatedSeatingPlan->save()) {
                Session::flash('alert-danger', 'Cannot save Seating Plan');
                Redirect::back();
            }
        }

        foreach ($event->information as $information) {
            $duplicatedInformation               = new EventInformation();
            $duplicatedInformation->event_id     = $duplicatedEvent->id;
            $duplicatedInformation->title        = $information->title;
            $duplicatedInformation->text         = $information->text;
            $duplicatedInformation->order        = $information->order;

            if (!$duplicatedInformation->save()) {
                Session::flash('alert-danger', 'Cannot save Information');
                Redirect::back();
            }

            if ($information->hasMedia()) {
                $information->getMedia()->first()->copy($duplicatedInformation);
            }
        }

        Session::flash('alert-success', 'Successfully Duplicated Event!');
        return Redirect::to('admin/events/' . $duplicatedEvent->slug);
    }
}
