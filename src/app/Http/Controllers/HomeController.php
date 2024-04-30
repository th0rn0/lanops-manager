<?php

namespace App\Http\Controllers;

use DB;
use Auth;

use App\Models\Event;
use App\Models\NewsArticle;
use App\Models\EventTimetableData;
use App\Models\EventParticipant;

class HomeController extends Controller
{
    /**
     * Show Index Page
     * @return View
     */
    public function index()
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
            ->withNewsArticles(NewsArticle::limit(4)->orderBy('created_at', 'desc')->get())
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
}
