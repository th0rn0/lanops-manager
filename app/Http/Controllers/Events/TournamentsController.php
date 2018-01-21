<?php

namespace App\Http\Controllers\Events;

use Illuminate\Http\Request;

use DB;
use Auth;
use Session;
use App\User;
use App\Event;
use App\EventParticipant;
use App\EventParticipantType;
use App\EventTimetable;
use App\EventTimetableData;
use App\EventTournament;
use App\EventTournamentParticipant;
use App\EventTournamentTeam;
use DateTime;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;

class TournamentsController extends Controller
{
	/**
	 * Show Tournaments
	 * @param  $slug
	 * @return Tournaments      
	 */
	public function index($slug)
	{
		if (!is_numeric($slug)) {
			$event = Event::where('slug', $slug)->first();
		} else {
			$event = Event::where('id', $slug)->first();
		}
		return $event->tournaments;
	}

	/**
	 * Show Tournaments Page
	 * @param  $event_slug 
	 * @param  $tournament_slug
	 * @param  Request $request
	 * @return View
	 */
	public function show($event_slug, $tournament_slug, Request $request)
	{
		$signed_in = true;
		$user = Auth::user();
		if (!is_numeric($event_slug)) {
			$event = Event::where('slug', $event_slug)->first();
		} else {
			$event = Event::where('id', $event_slug)->first();
		}
		if (!is_numeric($tournament_slug)) {
			$tournament = EventTournament::where('slug', $tournament_slug)->first();
		} else {
			$tournament = EventTournament::where('id', $tournament_slug)->first();
		}

		$user->setActiveEventParticipant($event->id);
		if (!isset($user->active_event_participant)) {
			Session::flash('alert-danger', 'Please sign in with one of our Admins.');
			return Redirect::to('/')->withErrors('Please sign in with one of our Admins.');
		}
		if ($tournament->status == 'LIVE' || $tournament->status == 'COMPLETE') {
			$tournament->matches = $tournament->getChallongeMatches();
			$tournament->challonge_participants = $tournament->getChallongeParticipants();
		}
		return view('events.tournaments.show')->withTournament($tournament)->withEvent($event)->withUser($user)->withSignedIn($signed_in);
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
			$request->session()->flash('alert-danger', 'Signups not permitted at this time.');
			return Redirect::to('/events/' . $event->slug . '/tournaments/' . $tournament->slug);
		}
	 
		if (!$tournament->event->eventParticipants()->where('id', $request->event_participant_id)->first()) {
			$request->session()->flash('alert-danger', 'You are not signed in to this event.');
			return Redirect::to('/events/' . $event->slug . '/tournaments/' . $tournament->slug);
		}

		if ($tournament->getParticipant($request->event_participant_id)) {
			$request->session()->flash('alert-danger', 'You are already signed up to this tournament.');
			return Redirect::to('/events/' . $event->slug . '/tournaments/' . $tournament->slug);
		}

		$tournament_participant = new EventTournamentParticipant();

		$tournament_participant->event_participant_id = $request->event_participant_id;
		$tournament_participant->event_tournament_id = $tournament->id;
		$tournament_participant->event_tournament_team_id = @$request->event_tournament_team_id;

		$tournament_participant->save();

		if (!isset($request->event_tournament_team_id) || trim($request->event_tournament_team_id) == '') {
			if (!$tournament_participant->setChallongeParticipantId()) {
				$tournament_participant->delete();
				Session::flash('alert-danger', 'Cannot add participant. Please try again.');
				return Redirect::to('/events/' . $event->slug . '/tournaments/' . $tournament->slug)->withErrors('Database Error. Please Contact a Admin.');
			}
		}

		$request->session()->flash('alert-success', 'Successfully Registered!');
		return Redirect::to('/events/' . $event->slug . '/tournaments/' . $tournament->slug);
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
			$request->session()->flash('alert-danger', 'Signups not permitted at this time.');
			return Redirect::to('/events/' . $event->slug . '/tournaments/' . $tournament->slug);
		}
	 
		if (!$tournament->event->eventParticipants()->where('id', $request->event_participant_id)->first()) {
			$request->session()->flash('alert-danger', 'You are not signed in to this event.');
			return Redirect::to('/events/' . $event->slug . '/tournaments/' . $tournament->slug);
		}

		if($tournament->getParticipant($request->event_participant_id)){
			$request->session()->flash('alert-danger', 'You are already signed up to this tournament.');
			return Redirect::to('/events/' . $event->slug . '/tournaments/' . $tournament->slug);
		}

		$tournament_team = new EventTournamentTeam();
		$tournament_team->event_tournament_id = $tournament->id;
		$tournament_team->name = $request->team_name;

		$tournament_team->save();

		if (!$tournament_team->setChallongeParticipantId()) {
			$tournament_team->delete();
			Session::flash('alert-danger', 'Cannot add participant. Please try again.');
			return Redirect::to('/events/' . $event->slug . '/tournaments/' . $tournament->slug)->withErrors('Database Error. Please Contact a Admin.');
		} 

		$request->session()->flash('alert-success', 'Team Successfully Created!');
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
			$request->session()->flash('alert-danger', 'Signups not permitted at this time.');
			return Redirect::to('/events/' . $event->slug . '/tournaments/' . $tournament->slug);
		}
	 
		if (!$tournament->event->eventParticipants()->where('id', $request->event_participant_id)->first()) {
			$request->session()->flash('alert-danger', 'You are not signed in to this event.');
			return Redirect::to('/events/' . $event->slug . '/tournaments/' . $tournament->slug);
		}

		if($tournament->getParticipant($request->event_participant_id)){
			$request->session()->flash('alert-danger', 'You are already signed up to this tournament.');
			return Redirect::to('/events/' . $event->slug . '/tournaments/' . $tournament->slug);
		}

		$tournament_participant = new EventTournamentParticipant();
		$tournament_participant->event_participant_id = $request->event_participant_id;
		$tournament_participant->event_tournament_id = $tournament->id;
		$tournament_participant->pug = true;

		$tournament_participant->save();

		$request->session()->flash('alert-success', 'Successfully Registered!');
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
		if(!$tournament_participant = $tournament->getParticipant($request->event_participant_id)){
			$request->session()->flash('alert-danger', 'You are not signed up.');
			return Redirect::to('/events/' . $event->slug . '/tournaments/' . $tournament->slug)->withErrors('Cannot find Participant.');
		}

		if (!$tournament_participant->delete()) {
			$request->session()->flash('alert-danger', 'Cannot remove. Please try again.');
			return Redirect::to('/events/' . $event->slug . '/tournaments/' . $tournament->slug);
		}

		$request->session()->flash('alert-success', 'You have been successfully removed from the Tournament.');
		return Redirect::to('/events/' . $event->slug . '/tournaments/' . $tournament->slug);
	}
}
