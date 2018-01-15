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
	/* View All Event.
	*
	* @param  \App
	* @return status
	*/
	public function all()
	{
		$events = Event::all();
		return view('events.index')->withEvents($events);
	}
	/* View Specific Event.
	*
	* @param  \App\Event  $event
	* @param  \App
	* @return status
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
			$timetable->data = EventTimetableData::where('event_timetable_id', $timetable->id)->orderBy('slot_timestamp', 'asc')->get();
		}
		//Get this users participant details if logged in
		$user = Auth::user();
		if ($user) {
			$clauses = ['user_id' => $user->id, 'event_id' => $event->id]; 
			$user->eventParticipation = EventParticipant::where($clauses)->get();
		}
		//Determine whether the user has a ticket for this event
		$ticket_flag = false;
		if ($user) {
			if ($user->eventParticipation != null || isset($user->eventParticipation)) {
				foreach ($user->eventParticipation as $participant) {
					if ($participant->event_id == $event->id) {
						$ticket_flag = true;
					} 
				}
			}
		}
		return view('events.show')->withEvent($event)->withTicketFlag($ticket_flag);

	}
}
