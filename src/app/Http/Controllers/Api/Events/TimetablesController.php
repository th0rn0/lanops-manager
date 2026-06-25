<?php

namespace App\Http\Controllers\Api\Events;

use App\Models\Event;

use App\Http\Controllers\Controller;

class TimetablesController extends Controller
{
    public function index($event)
    {
        $event = $this->resolveEvent($event);

        $return = [];
        foreach ($event->timetables as $timetable) {
            $return[] = $this->formatTimetable($timetable);
        }
        return $return;
    }

    public function showPrimary($event)
    {
        $event = $this->resolveEvent($event);

        $timetable = $event->timetables()->where('primary', true)->first();

        if (!$timetable) {
            abort(404);
        }

        return $this->formatTimetable($timetable);
    }

    public function show($event, $timetable)
    {
        $event = $this->resolveEvent($event);

        $timetable = $event->timetables()->where('slug', $timetable)->first()
            ?? $event->timetables()->where('id', $timetable)->first();

        if (!$timetable) {
            abort(404);
        }

        return $this->formatTimetable($timetable);
    }

    private function resolveEvent($event)
    {
        if (is_numeric($event)) {
            $resolved = Event::where('id', $event)->first();
        } else {
            $resolved = Event::where('slug', $event)->first();
        }

        if (!$resolved) {
            abort(404);
        }

        return $resolved;
    }

    private function formatTimetable($timetable)
    {
        $data = [];
        foreach ($timetable->data as $entry) {
            $data[] = [
                'start_time' => $entry->start_time,
                'name' => $entry->name,
                'description' => $entry->desc,
            ];
        }

        return [
            'name' => $timetable->name,
            'slug' => $timetable->slug,
            'status' => $timetable->status,
            'default' => (bool) $timetable->primary,
            'data' => $data,
        ];
    }
}
