<?php

namespace App\Http\Controllers\Events;

use DB;
use Auth;
use Session;

use App\User;
use App\Event;
use App\EventParticipant;
use App\EventTicket;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\PaymentController as Payment;

class TicketsController extends Controller
{
    /**
     * API Show all Event Tickets
     * @param  Event  $event
     * @return EventTickets
     */
    public function index($event)
    {
        $event = Event::where('id', $event)->first();
        return $event->tickets;
    }

    /**
     * API Show Event Ticket
     * @param  Event       $event
     * @param  EventTicket $ticket
     * @return EventTicket
     */
    public function show($event, $ticket)
    {
        $event = Event::where('id', $event)->first();
        $ticket = EventTicket::where('id', $ticket)->first();
        return $ticket;
    }

    /**
     * Purchase Ticket
     * @param  Request     $request
     * @param  EventTicket $ticket
     * @return Redirect
     */
    public function purchase(Request $request, EventTicket $ticket)
    {
        $user = User::where('id', $request->user_id)->first();

        if ($user == null) {
            Session::flash('alert-danger', 'User not found.');
            return Redirect::to('/events/' . $ticket->event->slug);
        }

        if ($ticket->event->status != 'PUBLISHED' && $ticket->event->status != 'PRIVATE') {
            Session::flash(
                'alert-danger',
                'Event is currently in ' . strtolower($ticket->event->status) . '. You cannot buy tickets yet.'
            );
            return Redirect::to('/events/' . $ticket->event->slug);
        }

        if (date('Y-m-d H:i:s') >= $ticket->event->end) {
            Session::flash('alert-danger', 'You cannot buy tickets for previous events.');
            return Redirect::to('/events/' . $ticket->event->slug);
        }

        if ($ticket->sale_start != null && date('Y-m-d H:i:s') <= $ticket->sale_start) {
            Session::flash('alert-danger', 'You cannot buy this ticket yet');
            return Redirect::to('/events/' . $ticket->event->slug);
        }

        if ($ticket->sale_end != null && date('Y-m-d H:i:s') >= $ticket->sale_end) {
            Session::flash('alert-danger', 'You cannot buy this ticket anymore');
            return Redirect::to('/events/' . $ticket->event->slug);
        }

        if (Session::get('basket')) {
            Session::forget('basket');
        }

        Session::put('basket', [$ticket->id => $request->quantity]);
        return Redirect::to('/payment/checkout');
    }

    /**
     * Retrieve ticket via QR code
     * @param  EventParticipant $participant
     * @return Redirect
     */
    public function retrieve(EventParticipant $participant)
    {
        $user = Auth::user();
        if ($user->admin == 1) {
            return Redirect::to('/admin/events/' . $participant->event_id . '/participants/' . $participant->id); // redirect to site
        }
        return Redirect::to('/events/' . $participant->event_id); // redirect to site
    }
}
