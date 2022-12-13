<?php

namespace App\Http\Controllers\Api\Events;

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
     * Show Participants
     * @param  $event
     * @return EventParticipants
     */
    public function index($event)
    {
        if (is_numeric($event)) {
            $event = Event::where('id', $event)->first();
        } else {
            $event = Event::where('slug', $event)->first();
        }

        if (!$event) {
            abort(404);
        }

        if ($event->private_participants)
        {
            abort(403); 
        }

        $return = array();

        foreach ($event->eventParticipants as $participant) {
            // $x["id"] = $participant->id;
            // $x["user_id"] = $participant->user_id;
            // $x["ticket_id"] = $participant->ticket_id;
            // $x["gift"] = $participant->gift;
            // $x["gift_sendee"] = $participant->gift_sendee;
            $seat = "Not Seated";
            if ($participant->seat) {
                $seat = $participant->seat->seat;
            }
            $return[] = [
                'username' => $participant->user->steamname ?? $participant->user->username,
                'seat' => $seat,
            ];
            // $x['user']['steamname'] = $participant->user->steamname;
            // $x['user']['username'] = $participant->user->username;
        }

        return $return;
    }
}
