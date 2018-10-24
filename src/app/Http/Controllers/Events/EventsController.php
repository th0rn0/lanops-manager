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
		if ($user) {
			$clauses = ['user_id' => $user->id, 'event_id' => $event->id]; 
			$user->eventParticipation = EventParticipant::where($clauses)->get();
		}
		return view('events.show')->withEvent($event);
	}
}
