<?php

namespace App\Http\Controllers\Admin\Events;

use Illuminate\Http\Request;

use DB;
use Auth;
use Session;
use App\User;
use App\Event;
use App\EventParticipant;
use App\EventTicket;
use App\EventAnnoucement;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;

class EventsController extends Controller
{
	/**
	 * Show Events Page Index
	 * @return View
	 */
	public function index()
	{
		$user = Auth::user();
		$events = Event::withoutGlobalScopes()->get();
		return view('admin.events.index')->withUser($user)->withEvents($events);
	}

	/**
	 * Show Events Page
	 * @param  Event  $event
	 * @return View
	 */
	public function show(Event $event)
	{
		$user = Auth::user();
		$events = Event::withoutGlobalScopes();
		$event->eventParticipants = $event->eventParticipants()->orderBy('created_at', 'desc')->simplePaginate(10);
		return view('admin.events.show')->withUser($user)->withEvent($event)->withEvents($events);
	}

	/**
	 * Add Event to Database
	 * @param  Request $request
	 * @return Redirect
	 */
	public function store(Request $request)
	{
		$rules = [
			'event_name'  => 'required|unique:events,display_name',
			'start_date'  => 'required',
			'start_time'  => 'required',
			'end_date'    => 'required',
			'end_time'    => 'required',
			'desc_short'  => 'required',
			'desc_long'   => 'required',
		];
		$messages = [
			'event_name.required'   => 'An Event name is required',
			'start_date.required'   => 'A Start date is required',
			'start_time.required'   => 'A Start date is required',
			'end_date.required'     => 'A End date is required',
			'end_time.required'     => 'A End date is required',
			'desc_short.required'   => 'A Short description is required',
			'desc_long.required'    => 'A description is required',
		];
		$this->validate($request, $rules, $messages);

		$event = new Event();
		//Format start and end time/date
		$start = $request->start_date . $request->start_time;
		$end = $request->end_date . $request->end_time;

		$event->display_name      = $request->event_name;
		$event->nice_name         = strtolower(str_replace(' ', '-', $request->event_name));
		$event->start             = date("Y-m-d H:i:s", strtotime($start));
		$event->end               = date("Y-m-d H:i:s", strtotime($end));
		$event->desc_long         = $request->desc_long;
		$event->desc_short        = $request->desc_short;
		$event->allow_spectators  = $request->allow_spec;
		$event->event_venue_id    = @$request->venue;

		if(!$event->save()){
			Session::flash('alert-danger', 'Could not Save!');
			return Redirect::to('admin/events/' . $event->id);
		}
		Session::flash('alert-success', 'Event Successfully Added!');
		return Redirect::to('admin/events/' . $event->id);
	}
	
	/**
	 * Update Event
	 * @param  Event   $event
	 * @param  Request $request
	 * @return Redirect
	 */
	public function update(Event $event, Request $request)
	{
		$rules = [
			'event_name'  => 'required',
			'desc_short'  => 'required',
			'desc_long'   => 'required',
			'end_date'    => 'required',
			'end_time'    => 'required',
			'start_date'  => 'required',
			'start_time'  => 'required',
			'status'      => 'in:draft,preview,published,private',
		];
		$messages = [
			'event_name|required'   => 'Event Name is required',
			'desc_short|required'   => 'A Short Description is required',
			'desc_long|required'    => 'A Long Description is required',
			'cap|required'          => 'A Capacity is required',
			'end_date|required'     => 'A End Date is required',
			'end_time|required'     => 'A End Time is required',
			'start_date|required'   => 'A Start Date is required',
			'start_time|required'   => 'A Start Time is required',
			'status|in'             => 'Status must be draft, preview, published or private',
		];
		$this->validate($request, $rules, $messages);

		//Format start and end time/date
		$start = $request->start_date . $request->start_time;
		$end = $request->end_date . $request->end_time;

		$event->display_name      = $request->event_name;
		$event->nice_name         = strtolower(str_replace(' ', '-', $request->event_name));
		$event->start             = date("Y-m-d H:i:s", strtotime($start));
		$event->end               = date("Y-m-d H:i:s", strtotime($end));
		$event->desc_long         = $request->desc_long;
		$event->desc_short        = $request->desc_short;
		$event->status            = @$request->status;
		$event->allow_spectators  = false;
		
		if (isset($request->allow_spec)) {
			$event->allow_spectators = true;
		}

		if(!$event->save()){
			Session::flash('alert-danger', 'Could not Save!');
			return Redirect::to('admin/events/' . $event->id);
		}
		Session::flash('alert-success', 'Event Successfully Updated!');
		return Redirect::to('admin/events/' . $event->id);
	}

	/**
	 * Delete Event from Database
	 * @param  Event  $event
	 * @return Redirect
	 */
	public function destroy(Event $event)
	{
		if (!$event->eventParticipants->isEmpty()) {
			Session::flash('alert-danger', 'Cannot delete event with participants!');
			return Redirect::back();
		}
		if (!$event->delete()) {
			Session::flash('alert-danger', 'Cannot Delete!');
			return Redirect::back();
		}
		Session::flash('alert-success', 'Successfully deleted!');
		return Redirect::to('admin/events/');
	}

	/**
	 * Add Gift Participant
	 * @param  Request $request
	 * @param  Event   $event
	 * @return Redirect
	 */
	public function freeGift(Request $request, Event $event)
	{
		$participant = new EventParticipant;

		$participant->user_id                = $request->user_id;
		$participant->event_id               = $event->id;
		$participant->free                   = 1;
		$participant->staff_free_assigned_by = Auth::id();
		$participant->generateQRCode();

		if (!$participant->save()) {
			Session::flash('alert-danger', 'Could not add Gift!');
			return Redirect::to('admin/events/' . $event->id . '/tickets');          
		}
		Session::flash('alert-success', 'Successfully added Gift!');
		return Redirect::to('admin/events/' . $event->id . '/tickets');
	}

	/**
	 * Add Admin Participant
	 * @param  Request $request
	 * @param  Event   $event
	 * @return Redirect 
	 */
	public function freeStaff(Request $request, Event $event)
	{
		$participant = new EventParticipant;

		$participant->user_id                = $request->user_id;
		$participant->event_id               = $event->id;
		$participant->staff                  = 1;
		$participant->staff_free_assigned_by = Auth::id();
		$participant->generateQRCode();
	 
		if (!$participant->save()) {
			Session::flash('alert-danger', 'Could not add Admin!');
			return Redirect::to('admin/events/' . $event->id . '/tickets');          
		}
		Session::flash('alert-success', 'Successfully added Admin!');
		return Redirect::to('admin/events/' . $event->id . '/tickets');
	}
}

