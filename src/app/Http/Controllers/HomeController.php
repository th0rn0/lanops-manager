<?php

namespace App\Http\Controllers;

use DB;
use Auth;

use App\Event;
use App\NewsArticle;
use App\EventTimetableData;
use App\EventParticipant;

class HomeController extends Controller
{
    /**
     * Show Index Page
     * @return Function
     */
    public function index()
    {
        // Check for Event
        $user = Auth::user();
        if ($user && !empty($user->eventParticipants)) {
            foreach ($user->eventParticipants as $participant) {
                if ((date('Y-m-d H:i:s') >= $participant->event->start) &&
                    (date('Y-m-d H:i:s') <= $participant->event->end) &&
                    $participant->signed_in
                ) {
                    return $this->event();
                }
            }
        }
        return $this->net();
    }

    /**
     * Show New Page
     * @return View
     */
    public function net()
    {
        $topAttendees = array();
        foreach (EventParticipant::groupBy('user_id', 'event_id')->get() as $attendee) {
            if ($attendee->event && $attendee->event->status == 'PUBLISHED' && $attendee->event->end < \Carbon\Carbon::today()) {
                $recent = false;
                if (!$attendee->user->admin && array_key_exists($attendee->user->id, $topAttendees)) {
                    $topAttendees[$attendee->user->id]->event_count++;
                    $recent = true;
                }
                if (!$attendee->user->admin && !$recent) {
                    $attendee->user->event_count = 1;
                    $topAttendees[$attendee->user->id] = $attendee->user;
                }
            }
        }
        usort($topAttendees, function ($a, $b) {
            return $b['event_count'] <=> $a['event_count'];
        });

        $topWinners = array();

        usort($topWinners, function ($a, $b) {
            return $b['win_count'] <=> $a['win_count'];
        });

        // TODO - TEMP FIX
        // Setup Slider Images
        $sliderImages = array(
            array(
                "path" => "/images/frontpage/slider/1.jpg"
            ),
            array (
                "path" => "/images/frontpage/slider/2.jpg"
            )
        );

        return view("home")
            ->withNextEvent(
                Event::where('end', '>=', \Carbon\Carbon::now())
                    ->orderBy(DB::raw('ABS(DATEDIFF(events.end, NOW()))'))->first()
            )
            ->withTopAttendees(array_slice($topAttendees, 0, 5))
            ->withTopWinners(array_slice($topWinners, 0, 5))
            ->withNewsArticles(NewsArticle::limit(2)->orderBy('created_at', 'desc')->get())
            ->withEvents(Event::all())
            ->withSliderImages(json_decode(json_encode($sliderImages), FALSE))
        ;
    }
    
    /**
     * Show About us Page
     * @return View
     */
    public function about()
    {
        return view("about");
    }
    
    /**
     * Show Terms and Conditions Page
     * @return View
     */
    public function terms()
    {
        return view("terms");
    }
    /**
     * Show Contact Page
     * @return View
     */
    public function contact()
    {
        return view("contact");
    }
    
    /**
     * Show Event Page
     * @return View
     */
    public function event()
    {
        $signedIn = true;
        $event = Event::where('start', '<', date("Y-m-d H:i:s"))->orderBy('id', 'desc')->first();
        $event->load('eventParticipants.user');
        $event->load('timetables');
        foreach ($event->timetables as $timetable) {
            $timetable->data = EventTimetableData::where('event_timetable_id', $timetable->id)
                ->orderBy('start_time', 'asc')
                ->get();
        }

        // TODO - Refactor
        $user = Auth::user();
        if ($user) {
            $clauses = ['user_id' => $user->id, 'event_id' => $event->id];
            $user->event_participation = EventParticipant::where($clauses)->get();
        }

        $ticketFlag = false;
        if ($user) {
            $user->setActiveEventParticipant($event->id);
            if ($user->eventParticipation != null || isset($user->eventParticipation)) {
                foreach ($user->eventParticipation as $participant) {
                    if ($participant->event_id == $event->id) {
                        $ticketFlag = true;
                    }
                }
            }
        }
        return view("events.home")
            ->withEvent($event)
            ->withTicketFlag($ticketFlag)
            ->withSignedIn($signedIn)
            ->withUser($user);
    }

    /**
     * Show Big Screen Page
     * @param  Event  $event
     * @return View
     */
    public function bigScreen(Event $event)
    {
        return view("events.big")->withEvent($event);
    }
}
