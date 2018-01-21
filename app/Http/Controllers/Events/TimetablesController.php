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

	/**
	 * Show all Timetables
	 * @param  Event  $event
	 * @return EventTimetables       
	 */
	public function index(Event $event)
	{
		return $event->timetables;
	}

	/**
	 * Show a Specific Timetable
	 * @param  Event          $event    
	 * @param  EventTimetable $timetable
	 * @return EventTimetable
	 */
	public function show(Event $event, EventTimetable $timetable)
	{
		return $timetable;
	}
}
