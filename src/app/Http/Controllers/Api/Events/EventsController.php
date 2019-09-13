<?php

namespace App\Http\Controllers\Api\Events;

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

class EventsController extends Controller
{
    /**
     * Show Events
     * @return View
     */
    public function index()
    {
        return Event::all();
    }

    /**
     * Show Upcoming Events
     * @return View
     */
    public function showUpcoming()
    {
        return Event::where('start', '>', \Carbon\Carbon::today())->get();
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

        return $event;
    }
}
