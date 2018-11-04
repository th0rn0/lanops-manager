<?php

namespace App\Http\Controllers\Events;

use DB;
use Auth;
use Session;
use App\User;
use App\Event;
use App\EventParticipant;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;

class ParticipantsController extends Controller
{
	/**
	 * API Show Participants
	 * @param  Event  $event
	 * @return EventParticipants
	 */
	public function show($event)
	{
		if (is_numeric($event)) {
			$event = Event::where('id', $event)->first();
		} else {
			$event = Event::where('slug', $event)->first();
		}

		if (!$event) {
			return Redirect::to('404');
		}

		$return = array();
		$x = array();
		foreach ($event->eventParticipants as $participant) {
			$x["id"] = $participant->id;
			$x["user_id"] = $participant->user_id;
			$x["ticket_id"] = $participant->ticket_id;
			$x["gift"] = $participant->gift;
			$x["gift_sendee"] = $participant->gift_sendee;
			$x['user']['steamname'] = $participant->user->steamname;
			$x['seat'] = "Not Seated";
			if ($participant->seat) {
				$x['seat'] = $participant->seat->seat;
			}
			array_push($return, $x);
		}

		return $return;
	}

	/**
	 * Gift Ticket
	 * @param  EventParticipant $participant
	 * @param  Request          $request
	 * @return Redirect 
	 */
	public function gift(EventParticipant $participant, Request $request)
	{
		if ($participant->gift != TRUE && $participant->gift_sendee == NULL) {
			$participant->gift = TRUE;
			$participant->gift_accepted = FALSE;
			$participant->gift_accepted_url = base_convert(microtime(false), 10, 36);
			$participant->gift_sendee = $participant->user_id;
			if ($participant->save()) {
				$request->session()->flash('alert-success', 'Ticket gifted Successfully! - Give your friend the URL below.');
				return Redirect::back();
			}
			$request->session()->flash('alert-danger', 'Somthing went wrong. Please try again later.');
			return Redirect::back();
		}
		$request->session()->flash('alert-danger', 'This Ticket has already Gifted.');
		return Redirect::back();
	}
	
	/**
	 * Revoke Gifted Ticket
	 * @param  EventParticipant $participant
	 * @param  boolean          $accepted
	 * @return Redirect
	 */
	public function revokeGift(EventParticipant $participant, $accepted = FALSE)
	{
		if ($participant->gift == TRUE) {
			if ($participant->gift_accepted != TRUE) {
				if ($accepted !== TRUE) {
					$participant->gift = NULL;
					$participant->gift_accepted = NULL;
					$participant->gift_sendee = NULL;
				}
				$participant->gift_accepted_url = NULL;
				if ($participant->save()) {
					Session::flash('alert-success', 'Ticket gift revoked Successfully!');
					return Redirect::back();
				}
			}
		} 
		Session::flash('alert-danger', 'This Ticket is already Gifted.');
		return Redirect::back();
	}
	
	/**
	 * Accept Gifted Ticket
	 * @param  Request $request
	 * @return Redirect
	 */
	public function acceptGift(Request $request)
	{
		$user = Auth::user();
		if ($user) {
			$participant = EventParticipant::where(['gift_accepted_url' => $request->url])->first();
			if ($participant != NULL) {
				$participant->gift_accepted = TRUE;
				$participant->user_id = $user->id;
				$participant->gift_accepted_url = NULL;
				if ($participant->save()) {
					$request->session()->flash('alert-success', 'Gift Successfully accepted! Please visit the event page to pick a seat');
					return Redirect::to('account');
				}
				$request->session()->flash('alert-danger', 'Something went wrong. Please try again later.');
				return Redirect::to('account');
			}
			$request->session()->flash('alert-danger', 'Redemption code not found.');
			return Redirect::to('home');
		}
		$request->session()->flash('alert-danger', 'Please Login.');
		return Redirect::to('home');
	}
}
