<?php

namespace App\Http\Controllers\Events;

use DB;
use Auth;
use Session;
use Helpers;

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
     * Seat Participant
     * @param  Event            $event
     * @param  EventSeatingPlan $seatingPlan
     * @param  Request          $request
     * @return Redirect
     */
    public function store(Event $event, EventSeatingPlan $seatingPlan, Request $request)
    {
        $rules = [
            'participant_id'    => 'required',
            'user_id'           => 'required',
            'seat_column'       => 'required',
            'seat_row'          => 'required',
        ];
        $messages = [
            'participant_id|required'   => 'A participant_id is required',
            'user_id|required'          => 'A user_id is required',
            'seat_column|required'      => 'A seat column is required',
            'seat_row|required'         => 'A seat row is required',
            
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
        if (!$event->getSeat($seatingPlan->id, $request->seat_column, $request->seat_row)) {
            //Seat does not Exists
            $seat                           = new EventSeating();
            $seat->column                   = $request->seat_column; 
            $seat->row                      = $request->seat_row;
            $seat->event_participant_id     = $participant->id;
            $seat->event_seating_plan_id    = $seatingPlan->id;
            $seat->save();
            $request->session()->flash(
                'alert-success',
                'You have been successfully assigned to seat ' . Helpers::getLatinAlphabetUpperLetterByIndex($seat->row) . $seat->column . ' in plan ' . $seatingPlan->name . '!'
            );
            return Redirect::to('events/' . $event->slug);
        }
        $request->session()->flash('alert-danger', 'That seat is alredy taken');
        return Redirect::to('events/' . $event->slug);
    }

    /**
     * Remove Participant Seating
     * @param  Event            $event
     * @param  EventSeatingPlan $seatingPlan
     * @param  Request          $request
     * @return Redirect
     */
    public function destroy(Event $event, EventSeatingPlan $seatingPlan, Request $request)
    {
        $rules = [
            'participant_id'        => 'required',
            'seat_column_delete'    => 'required',
            'seat_row_delete'       => 'required',
        ];
        $messages = [
            'participant_id|required'       => 'A participant_id is required',
            'seat_column_delete|required'   => 'A seat column is required',
            'seat_row_delete|required'      => 'A seat row is required',
            
        ];

        $this->validate($request, $rules, $messages);
        
        $clauses = [
            'event_participant_id'  => $request->participant_id,
            'column'                => $request->seat_column_delete,    
            'row'                   => $request->seat_row_delete,
            'event_seating_plan_id' => $seatingPlan->id
        ];

        if (!$seat = $seatingPlan->seats()->where($clauses)->first()) {
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
