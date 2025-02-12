<?php

namespace App\Http\Controllers\Events;

use Session;

use App\Models\Event;
use App\Models\EventSeating;
use App\Models\EventSeatingPlan;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;

class SeatingController extends Controller
{
    /**
     * Show Seating
     * @param  Event  $event
     * @return SeatingPlans
     */
    public function show(Event $event)
    {
        $seatingArray = array();
        foreach ($event->participants as $participant) {
            array_push($seatingArray, array($participant->seat => $participant->user->username));
        }
        return json_encode($seatingArray);
    }

    /**
     * Update Seat
     * @param  Event            $event
     * @param  EventSeatingPlan $seatingPlan
     * @param  Request          $request
     * @return Redirect
     */
    public function update(Event $event, EventSeatingPlan $seatingPlan, Request $request)
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
        
        if (!$participant = $event->EventParticipants()->where('id', $request->participant_id)->first()) {
            Session::flash('alert-danger', 'Event Participant not found');
            return Redirect::back();
        }

        if ($participant->ticket && !$participant->ticket->seatable) {
            Session::flash('alert-danger', 'That ticket is not seatable');
            return Redirect::back();
        }

        if ($participant->seat != null) {
            $participant->seat->event_participant_id = null;
            if (!$participant->seat->save()) {
                Session::flash('alert-danger', 'Could not remove seating');
                return Redirect::back();
            }
        }

        if (!$seat = $event->getSeat($seatingPlan->id, $request->seat)) {
            Session::flash('alert-danger', 'Could not find seat');
            return Redirect::back();
        }

        if ($seat->event_participant_id != null) {
            Session::flash('alert-danger', 'That seat is already taken!');
            return Redirect::back();
        }

        $seat->event_participant_id = $request->participant_id;

        if (!$seat->save()) {
            Session::flash('alert-danger', 'Could not update seating');
            return Redirect::back();
        }

        Session::flash('alert-success', 'You have been successfully seated in seat ' . $seat->seat . '!');
        return Redirect::back();
    }
}
