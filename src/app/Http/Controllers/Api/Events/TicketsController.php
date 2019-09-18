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

        $return = array();
        $x = array();
        foreach ($event->tickets as $ticket) {
            $x['name'] = $ticket->name;
            $x['type'] = $ticket->type;
            $x['price'] = $ticket->price;
            $x['quantity'] = $ticket->quantity;
            array_push($return, $x);
        }

        return $return;
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
            $ticket = EventTicket::where('id', $ticket)->first();
        } else {
            $ticket = EventTicket::where('slug', $ticket)->first();
        }
        if (!$event || !$ticket) {
            abort(404);
        }
        
        $return = array();
        $return['name'] = $ticket->name;
        $return['type'] = $ticket->type;
        $return['price'] = $ticket->price;
        $return['quantity'] = $ticket->quantity;

        return $return;
    }
}
