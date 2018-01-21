<?php

namespace App\Http\Controllers\Events;

use Illuminate\Http\Request;

use DB;
use Auth;
use App\Event;
use App\EventTimetable;
use App\EventTimetableData;
use App\EventParticipant;
use App\EventParticipantType;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;

class EventsController extends Controller
{
	/**
	 * Show Events Index Page
	 * @return View
	 */
	public function index()
	{
		$events = Event::all();
		return view('events.index')->withEvents($events);
	}
	
	/**
	 * Show Event Page
	 * @param  $slug
	 * @return View
	 */
	public function show($slug)
	{
		if (!is_numeric($slug)) {
			$event = Event::where('slug', $slug)->first();
		} else {
			$event = Event::where('id', $slug)->first();
		}
		if (!$event) {
			return Redirect::to('/');
		}
		foreach ($event->timetables as $timetable) {
			$timetable->data = EventTimetableData::where('event_timetable_id', $timetable->id)->orderBy('start_time', 'asc')->get();
		}
		$user = Auth::user();
		if ($user) {
			$clauses = ['user_id' => $user->id, 'event_id' => $event->id]; 
			$user->eventParticipation = EventParticipant::where($clauses)->get();
		}
		return view('events.show')->withEvent($event);
	}
}
