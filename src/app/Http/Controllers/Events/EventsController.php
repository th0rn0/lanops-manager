<?php

namespace App\Http\Controllers\Events;

use Auth;

use App\Event;
use App\EventParticipant;

use App\Http\Controllers\Controller;

class EventsController extends Controller
{
    /**
     * Show Events Index Page
     * @return View
     */
    public function index()
    {
        return view('events.index')->withEvents(Event::all());
    }
    
    /**
     * Show Event Page
     * @param  Event $event
     * @return View
     */
    public function show(Event $event)
    {
        $user = Auth::user();
        if ($user && !empty($user->eventParticipants)) {
            foreach ($user->eventParticipants as $participant) {
                if ($event->id == $participant->event->id
                    && (date('Y-m-d H:i:s') >= $participant->event->start)
                    && (date('Y-m-d H:i:s') <= $participant->event->end)
                    && $participant->signed_in
                ) {
                    return redirect('/');
                }
            }
        }
        if ($user) {
            $clauses = ['user_id' => $user->id, 'event_id' => $event->id];
            $user->eventParticipation = EventParticipant::where($clauses)->get();
        }
        if (!$event->polls->isEmpty()) {
            foreach ($event->polls as $poll) {
                $poll->sortOptions();
            }
        }
        $seoKeywords = explode(',',config('settings.seo_keywords'));
        $seoKeywords[] = $event->display_name;
        $seoKeywords[] = "Start Date: " . $event->start;
        return view('events.show')->withEvent($event);
    }
}
