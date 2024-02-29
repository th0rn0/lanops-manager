<?php

namespace App\Http\Controllers\Admin\Events;

use Session;

use App\Event;
use App\EventParticipant;

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
        return view('admin.events.participants.index')
            ->withEvent($event)
            ->withParticipants($event->eventParticipants()->paginate(20));
    }

    /**
     * Show Participants Page
     * @param  Event            $event
     * @param  EventParticipant $participant
     * @return View
     */
    public function show(Event $event, EventParticipant $participant)
    {
        return view('admin.events.participants.show')
            ->withEvent($event)
            ->withParticipant($participant);
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
        if (!$participant->setSignIn()) {
            Session::flash('alert-danger', 'Cannot sign in Participant!');
            return Redirect::to('admin/events/' . $event->slug . '/participants/' . $participant->id);
        }
        Session::flash('alert-success', 'Participant Signed in!');
        return Redirect::to('admin/events/' . $event->slug . '/participants/' . $participant->id);
    }

    public function transfer(Event $event, EventParticipant $participant, Request $request)
    {
        $rules = [
            'event_id'  => 'required',
            'event_id'  => 'exists:events,id',
        ];
        $messages = [
            'event_id|required' => 'A Event ID is required.',
            'event_id|exists'   => 'A Event ID must exist.',
        ];
        $this->validate($request, $rules, $messages);
        if ($participant->signed_in) {
            Session::flash('alert-warning', 'Cannot tranfer Participant already signed in!');
            return Redirect::to('admin/events/' . $event->slug . '/participants/' . $participant->id);
        }
        if (!$participant->transfer($request->event_id)) {
            Session::flash('alert-danger', 'Cannot tranfer Participant!');
            return Redirect::to('admin/events/' . $event->slug . '/participants/' . $participant->id);
        }
        Session::flash('alert-success', 'Participant Transferred!');
        return Redirect::to('admin/events/' . $event->slug . '/participants/');
    }
}
