<?php

namespace App\Http\Controllers\Events;

use Auth;

use App\Models\Event;
use App\Models\EventParticipant;

use App\Http\Controllers\Controller;

class EventsController extends Controller
{
    /**
     * Show Events Index Page
     * @return View
     */
    public function index()
    {
        seo()
            ->title('Events')
            ->description('Browse all gaming events hosted by ' . config('app.name') . '. Book your ticket online.')
            ->url(url('/events'));

        return view('events.index')->withEvents(Event::orderByDesc('start')->get());
    }
    
    /**
     * Show Event Page
     * @param  Event $event
     * @return View
     */
    public function show(Event $event)
    {
        seo()
            ->title($event->display_name)
            ->description(strip_tags(substr($event->desc_long, 0, 160)))
            ->url(url('/events/' . $event->slug));
        if ($event->venue && $event->venue->getFirstMediaUrl()) {
            seo()->image($event->venue->getFirstMediaUrl());
        }

        $user = Auth::user();
        if ($user) {
            $clauses = ['user_id' => $user->id, 'event_id' => $event->id];
            $user->eventParticipation = EventParticipant::where($clauses)->get();
        }
        if (!$event->polls->isEmpty()) {
            foreach ($event->polls as $poll) {
                $poll->sortOptions();
            }
        }
        return view('events.show')->withEvent($event);
    }
}
