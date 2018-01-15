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

class TimetableDataController extends Controller
{
	/**
	 * Store Timetable Data to Database
	 * @param  Event          $event
	 * @param  EventTimetable $timetable
	 * @param  Request        $request
	 * @return Redirect
	 */
	public function store(Event $event, EventTimetable $timetable, Request $request)
  	{
	    $data = new EventTimetableData;
	    $data->event_timetable_id = $timetable->id;
	    $data->slot_timestamp = $request->start;
	    $data->slot = $request->game;
	    $data->desc = $request->desc;
	    
	    if (!$data->save()) {
			Session::flash('alert-danger', 'Cannot save!');
		    return Redirect::back();
	    }
	    Session::flash('alert-success', 'Successfully Saved!');
	    return Redirect::back();
    }

    /**
     * Update Timetable Data
     * @param  Event              $event
     * @param  EventTimetable     $timetable
     * @param  EventTimetableData $data
     * @param  Request            $request
     * @return Redirect
     */
    public function update(Event $event, EventTimetable $timetable, EventTimetableData $data, Request $request)
    {
	    $data->slot_timestamp = $request->start;
    	$data->slot = $request->game;
	    $data->desc = $request->desc;

		if (!$data->save()) {
			Session::flash('alert-danger', 'Cannot save!');
		    return Redirect::back();
	    }
	    Session::flash('alert-success', 'Successfully Saved!');
	    return Redirect::back();
    }
}