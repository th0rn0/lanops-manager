<?php

namespace App\Http\Controllers\Api\Events;

use App\Models\Event;
use App\Models\EventTimetable;

use App\Http\Controllers\Controller;

class TimetablesController extends Controller
{

    /**
     * Show all Timetables
     * @param  $event
     * @return EventTimetables
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

        $event = Event::where('id', $event->id)->first();
        return $event->timetables;
    }

    /**
     * Show Timetable
     * @param  $event
     * @param  EventTimetable $timetable
     * @return EventTimetable
     */
    public function show($event, $timetable)
    {
        if (is_numeric($event)) {
            $event = Event::where('id', $event)->first();
        } else {
            $event = Event::where('slug', $event)->first();
        }
        if (is_numeric($timetable)) {
            $timetable = Event::where('id', $timetable)->first();
        } else {
            $timetable = Event::where('slug', $timetable)->first();
        }

        if (!$event || !$timetable) {
            abort(404);
        }

        $event = Event::where('id', $event)->first();
        $timetable = EventTimetable::where('id', $timetable)->first();
        return $timetable;
    }
}
