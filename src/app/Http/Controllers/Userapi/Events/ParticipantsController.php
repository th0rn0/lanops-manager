<?php

namespace App\Http\Controllers\Userapi\Events;


use App\Event;

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
                    ($participant->signed_in || $participant->event->online_event))
                {
                    $event = Event::where('start', '<', date("Y-m-d H:i:s"))->orderBy('id', 'desc')->first();
                }
            }
        }

        if (!isset($event)) {
            abort(404);
        }

        $return = array();
        foreach ($event->eventParticipants as $participant) {

            $seat = "Not Seated";
            if ($participant->seat) {
                $seat = $participant->seat->seat;
            }
            $return[] = [
                'id' => $participant->user->id,
                'name' => $participant->user->username,
                'admin' => $participant->user->admin,
                'seat' => $seat,
            ];
        }


        return $return;
    }
}
