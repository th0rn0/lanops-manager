<?php

namespace App\Http\Controllers\Admin\Events;

use Illuminate\Http\Request;

use DB;
use Session;
use App\User;
use App\Event;
use App\EventTicket;
use App\EventSeating;
use App\EventTimetable;
use App\EventTimetableData;
use App\EventParticipant;
use App\EventParticipantType;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;

class TimetablesController extends Controller
{
	/**
	 * Show Timetables Index Page
	 * @param  Event  $event
	 * @return View
	 */
	public function index(Event $event)
	{
		foreach($event->timetables as $timetable){
			$timetable->data = EventTimetableData::where('event_timetable_id', $timetable->id)->orderBy('start_time', 'asc')->get();
		}
		return view('admin.events.timetables.index')->withEvent($event);
	}

	/**
	 * Show Timetable Page
	 * @param  Event          $event
	 * @param  EventTimetable $timetable
	 * @return View
	 */
	public function show(Event $event, EventTimetable $timetable)
	{
		return view('admin.events.timetables.show')->withEvent($event)->withTimetable($timetable);
	}

	/**
	 * Store Timetable to Database
	 * @param  Event   $event
	 * @param  Request $request
	 * @return Redirect
	 */
	public function store(Event $event, Request $request)
	{
		$timetable              = new EventTimetable;
		$timetable->name  		= $request->name;
		$timetable->slug        = strtolower(str_replace(' ', '-', $request->name));
		$timetable->event_id 	= $event->id;

		if (!$timetable->save()) {
			Session::flash('alert-danger', 'Could not Save!');
			return Redirect::back();
		}
		Session::flash('alert-success', 'Successfully Saved!');
		return Redirect::to('admin/events/' . $event->slug . '/timetables/' . $timetable->id);
	}

	/**
	 * Update Timetable
	 * @param  Event          $event
	 * @param  EventTimetable $timetable
	 * @param  Request        $request
	 * @return redirect
	 */
	public function update(Event $event, EventTimetable $timetable, Request $request)
	{
		$rules = [
		 
		];
		$messages = [
		
		];
		$this->validate($request, $rules, $messages);

		$timetable->name  		= $request->name;
		$timetable->slug        = strtolower(str_replace(' ', '-', $request->name));
		$timetable->status 		= $request->status;
		$timetable->primary 	= ($request->primary ? true : false);

		if (!$timetable->save()) {
			Session::flash('alert-danger', 'Could not Save!');
			return Redirect::back();
		}
		Session::flash('alert-success', 'Successfully Saved!');
		return Redirect::back();
	}

	/**
	 * Delete Timetable from Database
	 * @param  Event          $event
	 * @param  EventTimetable $timetable
	 * @param  Request        $request
	 * @return Redirect
	 */
	public function destroy(Event $event, EventTimetable $timetable, Request $request)
	{
		if (!$timetable->delete()) {
			Session::flash('alert-danger', 'Could not Delete!');
			return Redirect::back();
		}
		Session::flash('alert-success', 'Successfully Deleted!');
		return Redirect::back();
	}
}
