<?php

namespace App\Http\Controllers\Api\Events;

use App\Models\Event;
use App\Models\EventTicket;

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
        foreach ($event->tickets as $ticket) {
            $return[] = [
                'name' => $ticket->name,
                'type' => $ticket->type,
                'price' => $ticket->price,
                'quantity' => $ticket->quantity,
            ];
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
        
        $return = [
            'name' => $ticket->name,
            'type' => $ticket->type,
            'price' => $ticket->price,
            'quantity' => $ticket->quantity,
        ];

        return $return;
    }
}
