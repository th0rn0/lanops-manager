<?php

namespace App\Http\Controllers\Events;

use Illuminate\Http\Request;

use DB;
use Auth;
use Session;
use App\User;
use App\Event;
use App\EventTicket;
use App\EventSeating;
use App\EventSeatingPlan;
use App\EventParticipant;
use App\EventParticipantType;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;


class SeatingController extends Controller
{
	public function show(Event $event)
	{
		//Create seating array
		$seating_array = array();
		foreach($event->participants as $participant){
			array_push($seating_array, array($participant->seat => $participant->user->username));
		}
		return json_encode($seating_array);
	}

	public function store(Event $event, EventSeatingPlan $seating_plan, Request $request)
	{
		$rules = [
			'participant_id'  => 'required',
			'user_id'         => 'required',
			'seat'            => 'required',
		];
		$messages = [
			'participant_id|required' => 'A participant_id name is required',
			'user_id|required'        => 'A user_id is required',
			'seat|required'           => 'A seat is required',
		];
		$this->validate($request, $rules, $messages);
		
		$participant = $event->EventParticipants()->where('id', $request->participant_id)->first();

		if ($participant->ticket && !$participant->ticket->seatable) {
			// Ticket not seatable
			Session::flash('alert-danger', 'That ticket is not seatable');
			return Redirect::to('events/' . $event->slug);
		}
		if ($participant->seat != null) {
			$participant->seat()->delete();
		}
		//Unseated ticket found
		if (!$event->getSeat($seating_plan->id, $request->seat)) {
			//Seat does not Exists
			$seat                         = new EventSeating;
			$seat->seat                   = $request->seat;
			$seat->event_participant_id   = $participant->id;
			$seat->event_seating_plan_id  = $seating_plan->id;
			$seat->save();
			$request->session()->flash('alert-success', 'You have been successfully seated in seat ' . $seat->seat . '!');
			return Redirect::to('events/' . $event->slug);
		}
		$request->session()->flash('alert-danger', 'That seat is alredy taken');
		return Redirect::to('events/' . $event->slug);
	}

	public function destroy(Event $event, EventSeatingPlan $seating_plan, Request $request)
	{
		$clauses = [
			'event_participant_id'  => $request->participant_id,
			'seat'                  => $request->seat_number,
			'event_seating_plan_id' => $seating_plan->id
		];
		if (!$seat = $seating_plan->seats()->where($clauses)->first()) {
			Session::flash('alert-danger', 'Could not find seating');
			return Redirect::back();
		}
		if (!$seat->delete()) {
			Session::flash('alert-danger', 'Could not remove seating');
			return Redirect::back();
		}
		Session::flash('alert-success', 'Successfully removed seating');
		return Redirect::back();
	}
}