<?php

namespace App\Http\Controllers\Events;

use DB;
use Auth;

use App\Event;
use App\EventTimetable;
use App\EventTimetableData;
use App\EventParticipant;
use App\EventParticipantType;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

use Artesaos\SEOTools\Facades\SEOTools;
use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\TwitterCard;
use Artesaos\SEOTools\Facades\JsonLd;

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
        SEOMeta::setDescription($event->desc_short);
        SEOMeta::addKeyword($seoKeywords);
        OpenGraph::setDescription($event->desc_short);
        OpenGraph::addProperty('type', 'article');
        return view('events.show')
            ->withEvent($event)
            ->withUser($user);
    }
}
