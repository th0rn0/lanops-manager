<?php

namespace App\Http\Controllers\Api\Events;

use DB;
use Auth;
use Session;
use Settings;

use App\User;
use App\Event;
use App\EventParticipant;
use App\EventTicket;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class TicketsController extends Controller
{
    /**
     * Show all Event Tickets
     * @param  $event
     * @return EventTickets
     */
    public function index($event)
    {
        if (is_numeric($event)) {
            $event = Event::where('id', $event)->first();
        } else {
            $event = Event::where('slug', $event)->first();
        }

        if (!$event) {
            abort(404);
        }

        $event = Event::where('id', $event)->first();
        return $event->tickets;
    }

    /**
     * Show Event Ticket
     * @param  $event
     * @param  EventTicket $ticket
     * @return EventTicket
     */
    public function show($event, $ticket)
    {
        if (is_numeric($event)) {
            $event = Event::where('id', $event)->first();
        } else {
            $event = Event::where('slug', $event)->first();
        }
     	if (is_numeric($ticket)) {
            $ticket = Event::where('id', $ticket)->first();
        } else {
            $ticket = Event::where('slug', $ticket)->first();
        }
        if (!$event || !$ticket) {
            abort(404);
        }

        $event = Event::where('id', $event)->first();
        $ticket = EventTicket::where('id', $ticket)->first();
        return $ticket;
    }
}
