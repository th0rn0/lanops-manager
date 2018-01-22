<?php

namespace App\Http\Controllers\Admin\Events;


use DB;
use Auth;

use App\User;
use App\Event;
use App\EventParticipant;
use App\EventTicket;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class ParticipantsController extends Controller
{
	/**
	 * Show Participants Index Page
	 * @param  Event  $event
	 * @return View
	 */
	public function index(Event $event)
	{
		return view('admin.events.participants.index')->withEvent($event);
	}

	/**
	 * Show Participants Page
	 * @param  Event            $event
	 * @param  EventParticipant $participant
	 * @return View
	 */
	public function show(Event $event, EventParticipant $participant)
	{
		return view('admin.events.participants.show')->withEvent($event)->withParticipant($participant);
	}

	/**
	 * Update Participant
	 * @param  Event            $event
	 * @param  EventParticipant $participant
	 * @param  Request          $request
	 */
	public function update(Event $event, EventParticipant $participant, Request $request)
	{
		//DEBUG
		dd('edit me');
	}

	/**
	 * Sign in to Event
	 * @param  Event            $event
	 * @param  EventParticipant $participant
	 * @return Redirect
	 */
	public function signIn(Event $event, EventParticipant $participant)
	{
		$participant->setSignIn();
		return Redirect::to('admin/events/' . $event->slug . '/participants/' . $participant->id);
	}
}

