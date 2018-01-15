<?php

namespace App\Http\Controllers\Events;

use Illuminate\Http\Request;

use DB;
use App\Event;
use App\EventParticipant;
use App\EventParticipantType;
use App\EventTimetable;
use App\EventTimetableData;
use DateTime;



use App\Http\Requests;
use App\Http\Controllers\Controller;

class TimetablesController extends Controller
{
	public function all(Event $event)
	{
		$event->load('timetables.data');
		return $event->timetables;
	}
	public function show(Event $event, EventTimetable $timetable)
	{
		$timetable->load('data');
		return $timetable;
	}
}
