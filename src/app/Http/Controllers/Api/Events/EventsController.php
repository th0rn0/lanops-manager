<?php

namespace App\Http\Controllers\Api\Events;


use App\Models\Event;

use App\Http\Controllers\Controller;


class EventsController extends Controller
{
    /**
     * Show Events
     * @return View
     */
    public function index()
    {
        $return = array();
        foreach (Event::all() as $event) {
            $return[] = $this->formatResponse($event);
        }
        return $return;
    }
    
    /**
     * Show Event
     * @param  $event
     * @return Event
     */
    public function show($event)
    {
        if (is_numeric($event)) {
            $event = Event::where('id', $event)->first();
        } else {
            $event = Event::where('slug', $event)->first();
        }

        if (!$event) {
            abort(404);
        }

        return $this->formatResponse($event);
    }

    /**
     * Show Upcoming Events
     * @return View
     */
    public function showUpcoming()
    {
        $return = array();
        foreach (Event::where('start', '>', \Carbon\Carbon::today())->get() as $event) {
            $return[] = $this->formatResponse($event);
        }
        return $return;
    }

    /**
     * Show Next Event
     * @return View
     */
    public function showNext()
    {
        $event = Event::where('start', '>', \Carbon\Carbon::today())->first();
        
        if (!$event) {
            abort(404);
        }
        return $this->formatResponse($event);
    }

    private function formatResponse($event)
    {
        $formattedResponse = array();
        $participants = array();
        foreach ($event->eventParticipants as $participant) {
            $seat = "Not Seated";
            if ($participant->seat) {
                $seat = $participant->seat->seat;
            }
            $participants[] = [
                'username' => $participant->user->steamname,
                'seat' => $seat,
                'id' => $participant->id
            ];
        }

        $tickets = array();
        foreach ($event->tickets as $ticket) {
            $tickets[] = [
                'name' => $ticket->name,
                'type' => $ticket->type,
                'price' => $ticket->price,
            ];
        }

        // $timetables = array();
        // foreach ($event->timetables as $timetable) {
        // }

        $formattedResponse = [
            'name' => $event->display_name,
            'capacity' => $event->capacity,
            'start' => $event->start,
            'end' => $event->end,
            'slug' => $event->slug,
            'description' => [
                'short' => $event->desc_short,
                'long' => $event->desc_long,
            ],
            'address' => [
                'line_1' => $event->venue->address_1,
                'line_2' => $event->venue->address_2,
                'street' => $event->venue->address_street,
                'city' => $event->venue->address_city,
                'postcode' => $event->venue->address_postcode,
                'country' => $event->venue->address_country,
            ],
            'url' => [
                'base' => config('app.url') . '/events/' . $event->slug,
                'tickets' => '#tickets',
                'participants' => '#participants',
                'timetables' => '#timetables',
            ],
            'participants' => $participants,
            'tickets' => $tickets,
            // 'timetables' => $event->timetables
        ];

        return $formattedResponse;
    }

}


