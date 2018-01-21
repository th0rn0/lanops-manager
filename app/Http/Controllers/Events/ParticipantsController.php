<?php

namespace App\Http\Controllers\Events;

use Illuminate\Http\Request;

use DB;
use Auth;
use Session;
use App\User;
use App\Event;
use App\EventParticipant;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;


class ParticipantsController extends Controller
{
	public function show(Event $event)
	{
		$return = array();
		$x = array();
		foreach ($event->eventParticipants as $participant) {
			$x["id"] = $participant->id;
			$x["user_id"] = $participant->user_id;
			$x["event_id"] = $participant->event_id;
			$x["ticket_id"] = $participant->ticket_id;
			$x["purchase_id"] = $participant->purchase_id;
			$x["qrcode"] = $participant->qrcode;
			$x["signed_in"] = $participant->signed_in;
			$x["gift"] = $participant->gift;
			$x["gift_accepted"] = $participant->gift_accepted;
			$x["gift_accepted_url"] = $participant->gift_accepted_url;
			$x["gift_sendee"] = $participant->gift_sendee;
			$x['user']['steamname'] = $participant->user->steamname;
			if ($participant->seat) {
				$x['seat'] = $participant->seat->seat;
			}
			array_push($return, $x);
		}

		return $return;
	}

	/* Show user the seats/tickets they bought for a specific event.
	 *
	 * @param  \App\Event  $event
	 * @param  \App\User  $user
	 * @return $thisUser
	 */
	public function showUser(Event $event, User $user)
	{
		$clauses = ['user_id' => $user->id, 'event_id' => $event->id];
		$thisUser = EventParticipant::where($clauses)->get();
		return $thisUser;
	}
	/* Gift a ticket.
	*
	* @param  \Illuminate\Http\Request  $request->user_id - Who to gift
	* @param  \Illuminate\Http\Request  $request->email - Email
	* @param  \Illuminate\Http\Request  $request->email_body - Email body
	* @param  \Illuminate\Http\Request  $request->email_signature - Email signature
	* @param  \App\EventParticipant  $participant
	* @return $participant
	*/
	public function gift(EventParticipant $participant, Request $request)
	{
		//Only accept non gifted
		if ($participant->gift != TRUE && $participant->gift_sendee == NULL) {
			$participant->gift = TRUE;
			$participant->gift_accepted = FALSE;
			//Generate gift accept URL
			$participant->gift_accepted_url = base_convert(microtime(false), 10, 36);
			$participant->gift_sendee = $participant->user_id;
			if ($participant->save()) {
				$request->session()->flash('alert-success', 'Ticket gifted Successfully! - Give your friend the URL below.');
				return Redirect::back();
			}
			$request->session()->flash('alert-danger', 'Somthing went wrong. Please try again later.');
			return Redirect::back();
		} else {
			$request->session()->flash('alert-danger', 'This Ticket has already Gifted.');
			return Redirect::back();
		}
	}
	/* Revoke a gift.
	*
	* @param  \App\EventParticipant  $participant
	* @return $participant
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
			} else {
				Session::flash('alert-danger', 'Gift has already been accepted.');
				return Redirect::back();
			}
		} else {
			Session::flash('alert-danger', 'This Ticket is already Gifted.');
			return Redirect::back();
		}
	}
	/* Accept a gift.
	*
	* @param  \Illuminate\Http\Request  $request->url - Users accept unique accept url
	* @return $participant
	*/
	public function acceptGift(Request $request)
	{
		$user = Auth::user();
		if ($user) {
			$participant = EventParticipant::where(['gift_accepted_url' => $request->url])->first();
			if ($participant != NULL) {
				//Set as gifted/complete
				$participant->gift_accepted = TRUE;
				//Change details to gifted user
				$participant->user_id = $user->id;
				//Remove gift status - TRUE = dont include accepted and gift
				$this->revokeGift($participant, TRUE);
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
	
	/* Remove User to Event Participants.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \App\Event  $event
	 * @param  \App\User  $user
	 * @return status
	 */
	public function remove(Request $request, Event $event, user $user)
	{
		//check if user is already signed up
		$clauses = ['user_id' => $user->id, 'event_id' => $event->id];
		$participant = EventParticipant::where($clauses)->first();
		if ($participant != NULL) {
			//User Exists
			$participant->delete();
			return 'true';
		} else {
			return 'user cannot be found';
		}
	}
	 /* Update User in Event Participants.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \App\Event  $event
	 * @param  \App\User  $user
	 * @return status
	 */
	public function update(Request $request, Event $event, user $user)
	{
		//check if user is already signed up
		$clauses = ['user_id' => $user->id, 'event_id' => $event->id];
		$participant = EventParticipant::where($clauses)->first();
		if ($participant != NULL) {
			//User Exists
			$participant->event_participant_type_id = $request->type_id;
			$participant->save();
			return 'true';
		} else {
			return 'user cannot be found';
		}
	}
	
}
