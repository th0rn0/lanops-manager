<?php

namespace App\Http\Controllers\Events;

use Illuminate\Http\Request;

use DB;
use Auth;
use Session;
use App\User;
use App\Event;
use App\EventParticipant;
use App\EventTicket;

use Illuminate\Support\Facades\Redirect;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\PaymentController as Payment;

class TicketsController extends Controller
{
	public function all(Event $event)
	{
		$event->load('tickets');
		return $event->tickets;
	}
	public function show(Event $event, EventTicket $ticket)
	{
		return $ticket;
	}
	public function purchase(Request $request, EventTicket $ticket)
	{
		//Purchase Route
		//Check user exists
		$user = User::where('id', $request->user_id)->first();

		if ($user == NULL) {
			Session::flash('alert-danger', 'User not found.');
			return Redirect::to('/events/' . $ticket->event->slug); 
		}

		if ($ticket->event->status != 'PUBLISHED' && $ticket->event->status != 'PRIVATE') {
			Session::flash('alert-danger', 'Event is currently in ' . strtolower($ticket->event->status) . '. You cannot buy tickets yet.');
			return Redirect::to('/events/' . $ticket->event->slug); 
		}

		if (date('Y-m-d H:i:s') >= $ticket->event->end) {
			Session::flash('alert-danger', 'You cannot buy tickets for previous events.');
			return Redirect::to('/events/' . $ticket->event->slug); 
		}

		if ($ticket->sale_start != null && date('Y-m-d H:i:s') <= $ticket->sale_start) {
			Session::flash('alert-danger', 'You cannot buy this ticket yet');
			return Redirect::to('/events/' . $ticket->event->slug); 
		}

		if ($ticket->sale_end != null && date('Y-m-d H:i:s') >= $ticket->sale_end) {
			Session::flash('alert-danger', 'You cannot buy this ticket anymore');
			return Redirect::to('/events/' . $ticket->event->slug); 
		}

		if (Session::get('basket')) {
			Session::forget('basket');
		}

		Session::put('basket', [$ticket->id => $request->quantity]);
		return Redirect::to('/payment/review'); 


		//DEBUG
		dd();
		$payment = new Payment;
		//DEBUG - Change payment to a model
		$payment->post($user, $ticket, $request->quantity);

		return $event . $ticket . $user;

	}
	public function retrieve(EventParticipant $participant)
	{
		$user = Auth::user();
		//Check if user is admin
		if($user->admin == 1){
			//User is admin - show lan registration
			return redirect('/admin/events/' . $participant->event_id . '/participants/' . $participant->id); // redirect to site
		} else {
			//User is not admin - show information
			return redirect('/events/' . $participant->event_id); // redirect to site
		}
		return $user;
	}
}
