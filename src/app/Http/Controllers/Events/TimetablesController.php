<?php

namespace App\Http\Controllers\Events;

use DB;
use DateTime;

use App\Event;
use App\EventParticipant;
use App\EventParticipantType;
use App\EventTimetable;
use App\EventTimetableData;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class TimetablesController extends Controller
{

    /**
     * API Show all Timetables
     * @param  Event  $event
     * @return EventTimetables
     */
    public function index($event)
    {
        $event = Event::where('id', $event)->first();
        return $event->timetables;
    }

    /**
     * API Show Timetable
     * @param  Event          $event
     * @param  EventTimetable $timetable
     * @return EventTimetable
     */
    public function show($event, $timetable)
    {
        $event = Event::where('id', $event)->first();
        $timetable = EventTimetable::where('id', $timetable)->first();
        return $timetable;
    }
}
