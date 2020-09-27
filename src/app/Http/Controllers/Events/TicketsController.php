<?php

namespace App\Http\Controllers\Events;

use DB;
use Auth;
use Session;
use Settings;

use App\User;
use App\Event;
use App\EventParticipant;
use App\EventTicket;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\PaymentController as Payment;

class TicketsController extends Controller
{
    /**
     * Purchase Ticket
     * @param  Request     $request
     * @param  EventTicket $ticket
     * @return Redirect
     */
    public function purchase(Request $request, EventTicket $ticket)
    {
        $user = User::where('id', $request->user_id)->first();

        if ($user == null) {
            Session::flash('alert-danger', 'User not found.');
            return Redirect::to('/events/' . $ticket->event->slug);
        }
        if ($ticket->event->status != 'PUBLISHED' && $ticket->event->status != 'PRIVATE') {
            Session::flash(
                'alert-danger',
                'Event is currently in ' . strtolower($ticket->event->status) . '. You cannot buy tickets yet.'
            );
            return Redirect::to('/events/' . $ticket->event->slug);
        }

        if (date('Y-m-d H:i:s') >= $ticket->event->end) {
            Session::flash('alert-danger', 'You cannot buy tickets for previous events.');
            return Redirect::to('/events/' . $ticket->event->slug);
        }

        if ($ticket->sale_start != null && date('Y-m-d H:i:s') <= $ticket->sale_start) {
            Session::flash('alert-danger', __('ticket_not_yet'));
            return Redirect::to('/events/' . $ticket->event->slug);
        }

        if ($ticket->sale_end != null && date('Y-m-d H:i:s') >= $ticket->sale_end) {
            Session::flash('alert-danger', __('ticket_not_anymore'));
            return Redirect::to('/events/' . $ticket->event->slug);
        }

        
        $user_event_tickets = $user->getAllTickets( $ticket->event->id);
        if(is_numeric($ticket->no_tickets_per_user) && $ticket->no_tickets_per_user > 0 && count($user_event_tickets) + $request->quantity > $ticket->no_tickets_per_user) {
            Session::flash('alert-danger', __('max_ticket_count_reached',['maxticketcount' =>  $ticket->no_tickets_per_user]));
            return Redirect::to('/events/' . $ticket->event->slug);
        }



        $params = [
            'tickets' => [
                $ticket->id => $request->quantity,
            ],
        ];
        Session::put(Settings::getOrgName() . '-basket', $params);
        Session::save();
        return Redirect::to('/payment/checkout');
    }

    /**
     * Retrieve ticket via QR code
     * @param  EventParticipant $participant
     * @return Redirect
     */
    public function retrieve(EventParticipant $participant)
    {
        $user = Auth::user();
        if ($user->admin == 1) {
            return Redirect::to('/admin/events/' . $participant->event_id . '/participants/' . $participant->id); // redirect to site
        }
        return Redirect::to('/events/' . $participant->event_id); // redirect to site
    }
}
