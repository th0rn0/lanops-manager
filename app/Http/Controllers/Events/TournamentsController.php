<?php

namespace App\Http\Controllers\Events;

use DB;
use Auth;
use Session;
use DateTime;

use App\User;
use App\Event;
use App\EventParticipant;
use App\EventParticipantType;
use App\EventTimetable;
use App\EventTimetableData;
use App\EventTournament;
use App\EventTournamentParticipant;
use App\EventTournamentTeam;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;

use Lanops\Challonge\Challonge;

class TournamentsController extends Controller
{
	/**
	 * Show Tournaments
	 * @param  Event $event
	 * @return Tournaments      
	 */
	public function index(Event $event)
	{
		return $event->tournaments;
	}

	/**
	 * Show Tournaments Page
	 * @param  Event 			$event 
	 * @param  EventTournament 	$tournament
	 * @param  Request 			$request
	 * @return View
	 */
	public function show(Event $event, EventTournament $tournament, Request $request)
	{
		$signed_in = true;
		if (!$user = Auth::user()) {
			Redirect::to('/');
		}
		$user->setActiveEventParticipant($event->id);
		if (!isset($user->active_event_participant)) {
			Session::flash('alert-danger', 'Please sign in with one of our Admins.');
			return Redirect::to('/')->withErrors('Please sign in with one of our Admins.');
		}
		return view('events.tournaments.show')
			->withTournament($tournament)
			->withEvent($event)
			->withUser($user)
			->withSignedIn($signed_in)
		;
	}

	/**
	 * Register to Tournament
	 * @param  Event           $event
	 * @param  EventTournament $tournament
	 * @param  Request         $request
	 * @return [type]                     
	 */
	public function register(Event $event, EventTournament $tournament, Request $request)
	{
		if ($tournament->status != 'OPEN') {
			Session::flash('alert-danger', 'Signups not permitted at this time.');
			return Redirect::back();
		}
	 
		if (!$tournament->event->eventParticipants()->where('id', $request->event_participant_id)->first()) {
			Session::flash('alert-danger', 'You are not signed in to this event.');
			return Redirect::back();
		}

		if ($tournament->getParticipant($request->event_participant_id)) {
			Session::flash('alert-danger', 'You are already signed up to this tournament.');
			return Redirect::back();
		}

		if (
			isset($request->event_tournament_team_id) &&
			$tournament_team = $tournament->tournamentTeams()->where('id', $request->event_tournament_team_id)->first()
		) {
			if($tournament_team->tournamentParticipants->count() == substr($tournament->team_size, 0, 1)) {
				Session::flash('alert-danger', 'This team is full.');
				return Redirect::back();
			}
		}
		$tournament_participant = new EventTournamentParticipant();

		$tournament_participant->event_participant_id = $request->event_participant_id;
		$tournament_participant->event_tournament_id = $tournament->id;
		$tournament_participant->event_tournament_team_id = @$request->event_tournament_team_id;

		if (!$tournament_participant->save()) {
			Session::flash('alert-danger', 'Cannot add participant. Please try again.');
			return Redirect::back();
		}

		if (!isset($request->event_tournament_team_id) || trim($request->event_tournament_team_id) == '') {
			// TODO - recreate setChallongeParticipantId so it is for the creation of the participant on challonge and uses the username.
			if (!$tournament_participant->setChallongeParticipantId()) {
				$tournament_participant->delete();
				Session::flash('alert-danger', 'Cannot add participant. Please try again.');
				return Redirect::back();
			}
		}

		Session::flash('alert-success', 'Successfully Registered!');
		return Redirect::back();
	}

	/**
	 * Register Team to Tournament
	 * @param  Event           $event
	 * @param  EventTournament $tournament
	 * @param  Request         $request
	 * @return Redirect  
	 */
	public function registerTeam(Event $event, EventTournament $tournament, Request $request)
	{
		if ($tournament->status != 'OPEN') {
			Session::flash('alert-danger', 'Signups not permitted at this time.');
			return Redirect::to('/events/' . $event->slug . '/tournaments/' . $tournament->slug);
		}
	 
		if (!$tournament->event->eventParticipants()->where('id', $request->event_participant_id)->first()) {
			Session::flash('alert-danger', 'You are not signed in to this event.');
			return Redirect::to('/events/' . $event->slug . '/tournaments/' . $tournament->slug);
		}

		if($tournament->getParticipant($request->event_participant_id)){
			Session::flash('alert-danger', 'You are already signed up to this tournament.');
			return Redirect::to('/events/' . $event->slug . '/tournaments/' . $tournament->slug);
		}

		$tournament_team 						= new EventTournamentTeam();
		$tournament_team->event_tournament_id 	= $tournament->id;
		$tournament_team->name 					= $request->team_name;

		$tournament_team->save();

		if (!$tournament_team->setChallongeParticipantId()) {
			$tournament_team->delete();
			Session::flash('alert-danger', 'Cannot add participant. Please try again.');
			return Redirect::to('/events/' . $event->slug . '/tournaments/' . $tournament->slug)->withErrors('Database Error. Please Contact a Admin.');
		} 

		Session::flash('alert-success', 'Team Successfully Created!');
		return Redirect::to('/events/' . $event->slug . '/tournaments/' . $tournament->slug);
	}

	/**
	 * Register Pug to Tournament
	 * @param  Event           $event
	 * @param  EventTournament $tournament
	 * @param  Request         $request
	 * @return Redirect 
	 */
	public function registerPug(Event $event, EventTournament $tournament, Request $request)
	{
		if ($tournament->status != 'OPEN') {
			Session::flash('alert-danger', 'Signups not permitted at this time.');
			return Redirect::to('/events/' . $event->slug . '/tournaments/' . $tournament->slug);
		}
	 
		if (!$tournament->event->eventParticipants()->where('id', $request->event_participant_id)->first()) {
			Session::flash('alert-danger', 'You are not signed in to this event.');
			return Redirect::to('/events/' . $event->slug . '/tournaments/' . $tournament->slug);
		}

		if($tournament->getParticipant($request->event_participant_id)){
			Session::flash('alert-danger', 'You are already signed up to this tournament.');
			return Redirect::to('/events/' . $event->slug . '/tournaments/' . $tournament->slug);
		}

		$tournament_participant = new EventTournamentParticipant();
		$tournament_participant->event_participant_id = $request->event_participant_id;
		$tournament_participant->event_tournament_id = $tournament->id;
		$tournament_participant->pug = true;

		$tournament_participant->save();

		Session::flash('alert-success', 'Successfully Registered!');
		return Redirect::to('/events/' . $event->slug . '/tournaments/' . $tournament->slug);
	}

	/**
	 * Unregister from Tournament
	 * @param  Event           $event
	 * @param  EventTournament $tournament
	 * @param  Request         $request
	 * @return Redirect
	 */
	public function unregister(Event $event, EventTournament $tournament, Request $request)
	{
		if (!$tournament_participant = $tournament->getParticipant($request->event_participant_id)) {
			Session::flash('alert-danger', 'You are not signed up.');
			return Redirect::to('/events/' . $event->slug . '/tournaments/' . $tournament->slug)->withErrors('Cannot find Participant.');
		}

		if (!$tournament_participant->delete()) {
			Session::flash('alert-danger', 'Cannot remove. Please try again.');
			return Redirect::to('/events/' . $event->slug . '/tournaments/' . $tournament->slug);
		}

		Session::flash('alert-success', 'You have been successfully removed from the Tournament.');
		return Redirect::to('/events/' . $event->slug . '/tournaments/' . $tournament->slug);
	}
}
