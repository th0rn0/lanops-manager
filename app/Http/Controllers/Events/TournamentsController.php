<?php

namespace App\Http\Controllers\Events;

use DB;
use Auth;
use Session;
use DateTime;

use App\User;
use App\Event;
use App\EventParticipant;
use App\EventTournament;
use App\EventTournamentParticipant;
use App\EventTournamentTeam;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;

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
		if (!$user = Auth::user()) {
			Redirect::to('/');
		}
		$user->setActiveEventParticipant($event->id);
		if (!isset($user->active_event_participant)) {
			Session::flash('alert-danger', 'Please sign in with one of our Admins.');
			return Redirect::to('/')->withErrors('Please sign in with one of our Admins.');
		}
		// TODO - Refactor - add the final scores to the tournament participant so getChallongeParticipants can be removed and add a job to pull it?
        if ($tournament->status == 'COMPLETE' && $tournament->format != 'list') {
            $tournament->challonge_participants = $tournament->getChallongeParticipants();
        }
		return view('events.tournaments.show')
			->withTournament($tournament)
			->withEvent($event)
			->withUser($user)
		;
	}

	/**
	 * Register to Tournament
	 * @param  Event           $event
	 * @param  EventTournament $tournament
	 * @param  Request         $request
	 * @return [type]                     
	 */
	public function registerSingle(Event $event, EventTournament $tournament, Request $request)
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
			if ($tournament_team->tournamentParticipants->count() == substr($tournament->team_size, 0, 1)) {
				Session::flash('alert-danger', 'This team is full.');
				return Redirect::back();
			}
		}

		// TODO - Refactor
		$tournament_participant 							= new EventTournamentParticipant();
		$tournament_participant->event_participant_id 		= $request->event_participant_id;
		$tournament_participant->event_tournament_id 		= $tournament->id;
		$tournament_participant->event_tournament_team_id 	= @$request->event_tournament_team_id;

		if (!$tournament_participant->save()) {
			Session::flash('alert-danger', 'Cannot add participant. Please try again.');
			return Redirect::back();
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
			return Redirect::back();
		}
	 
		if (!$tournament->event->eventParticipants()->where('id', $request->event_participant_id)->first()) {
			Session::flash('alert-danger', 'You are not signed in to this event.');
			return Redirect::back();
		}

		if($tournament->getParticipant($request->event_participant_id)){
			Session::flash('alert-danger', 'You are already signed up to this tournament.');
			return Redirect::back();
		}

		$tournament_team 						= new EventTournamentTeam();
		$tournament_team->event_tournament_id 	= $tournament->id;
		$tournament_team->name 					= $request->team_name;

		if (!$tournament_team->save()) {
			Session::flash('alert-danger', 'Cannot add Team. Please try again.');
			return Redirect::back();
		}

		// TODO - Refactor
		$tournament_participant 							= new EventTournamentParticipant();
		$tournament_participant->event_participant_id 		= $request->event_participant_id;
		$tournament_participant->event_tournament_id 		= $tournament->id;
		$tournament_participant->event_tournament_team_id 	= $tournament_team->id;

		if (!$tournament_participant->save()) {
			Session::flash('alert-danger', 'Cannot add participant. Please try again.');
			return Redirect::back();
		}

		Session::flash('alert-success', 'Team Successfully Created!');
		return Redirect::back();
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
			return Redirect::back();
		}
	 
		if (!$tournament->event->eventParticipants()->where('id', $request->event_participant_id)->first()) {
			Session::flash('alert-danger', 'You are not signed in to this event.');
			return Redirect::back();
		}

		if($tournament->getParticipant($request->event_participant_id)){
			Session::flash('alert-danger', 'You are already signed up to this tournament.');
			return Redirect::back();
		}

		$tournament_participant 						= new EventTournamentParticipant();
		$tournament_participant->event_participant_id 	= $request->event_participant_id;
		$tournament_participant->event_tournament_id 	= $tournament->id;
		$tournament_participant->pug 					= true;

		if (!$tournament_participant->save()) {
			Session::flash('alert-danger', 'Cannot add PUG. Please try again.');
			return Redirect::back();
		}

		Session::flash('alert-success', 'Successfully Registered!');
		return Redirect::back();
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
			return Redirect::back();
		}

		if (!$tournament_participant->delete()) {
			Session::flash('alert-danger', 'Cannot remove. Please try again.');
			return Redirect::back();
		}

		Session::flash('alert-success', 'You have been successfully removed from the Tournament.');
		return Redirect::back();
	}
}
