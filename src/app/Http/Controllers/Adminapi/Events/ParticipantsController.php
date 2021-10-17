<?php

namespace App\Http\Controllers\Adminapi\Events;


use App\Event;
use App\EventParticipant;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class ParticipantsController extends Controller
{
    /**
     * Show Participants
     * @param  $event
     * @return EventParticipants
     */
    public function getParticipants(Request $request)
    {
        $user = auth('sanctum')->user();

        if ($user && !empty($user->eventParticipants)) {
            foreach ($user->eventParticipants as $participant) {
                if ((date('Y-m-d H:i:s') >= $participant->event->start) &&
                    (date('Y-m-d H:i:s') <= $participant->event->end) &&
                    ($participant->signed_in || $participant->event->online_event)
                ) {
                    $event = Event::where('start', '<', date("Y-m-d H:i:s"))->where('end', '>', date("Y-m-d H:i:s"))->orderBy('id', 'desc')->first();
                    break;
                }
            }
        }

        if (!isset($event)) {
            abort(404, "Event not found.");
        }

        $return = array();
        $return["event"] = [
            'online_event' => $participant->event->online_event,
        ];

        foreach ($event->eventParticipants as $participant) {

            $seat = "Not Seated";
            if ($participant->seat) {
                $seat = $participant->seat->seat;
            }
            $return["participants"][] = [
                'participant' => $participant,
                'user' => $participant->user,
                'purchase' => $participant->purchase,
                'seat' => $seat,
            ];
        }


        return $return;
    }
    /**
     * Sign in to user to current Event
     * @param  EventParticipant $participant
     * @return Redirect
     */
    public function signIn(EventParticipant $participant)
    {
        if (!$participant->setSignIn()) {
            return [
                'successful' => 'false',
                'reason' => 'Cannot sign in Participant',
                'participant' => $participant,
            ];
        }
        return [
            'successful' => 'true',
            'reason' => '',
            'participant' => $participant,
        ];
    }
}
