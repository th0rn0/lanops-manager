<?php
namespace App\Http\Controllers;

use Auth;
use Session;

use App\Models\Tournament;
use App\Models\TournamentParticipant;
use App\Models\TournamentTeam;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;

class TournamentsController extends Controller
{
    /**
     * Show Tournaments
     * @return View
     */
    public function index()
    {
        return view('tournaments.index')
            ->withActiveTournaments(Tournament::whereNot('status', Tournament::$statusComplete)->get())
            ->withCompletedTournaments(Tournament::where('status', Tournament::$statusComplete)->paginate(20));
    }
    
    /**
     * Register to Tournament
     * @param  Tournament       $tournament
     * @param  Request          $request
     * @return [type]
     */
    public function register(Tournament $tournament, Request $request)
    {
        $rules = [];
        $messages = [];
        if (isset($request->event_id) && $request->event_id != 0) {
            $rules['tournament_team_id'] = 'exists:tournament_teams,id';
            $messages['tournament_team_id'] = 'Tournament Team does not exist';
        }
        $this->validate($request, $rules, $messages);

        if ($tournament->status != 'OPEN') {
            Session::flash('alert-danger', 'Signups not permitted at this time.');
            return Redirect::back();
        }

        if ($tournament->event_id && !$tournament->event->getEventParticipant()) {
            Session::flash('alert-danger', 'You are not signed in to this event.');
            return Redirect::back();
        }

        if ($tournament->isUserSignedUp(Auth::user())) {
            Session::flash('alert-danger', 'You are already signed up to this tournament.');
            return Redirect::back();
        }

        $tournamentParticipant                  = new TournamentParticipant();
        $tournamentParticipant->user_id         = Auth::id();
        $tournamentParticipant->tournament_id   = $tournament->id;
        $tournamentParticipant->tournament_team_id = null;
        if ($tournament->hasTeams() && isset($request->tournament_team_id) && $request->tournament_team_id != null) {
            $tournamentParticipant->tournament_team_id = $request->tournament_team_id;
        }
        if (!$tournamentParticipant->save()) {
            Session::flash('alert-danger', 'Cannot add participant. Please try again.');
            return Redirect::back();
        }
        Session::flash('alert-success', 'Successfully Registered!');
        return Redirect::back();
    }

    /**
     * Register Team to Tournament
     * @param  Tournament $tournament
     * @param  Request         $request
     * @return Redirect
     */
    public function registerTeam(Tournament $tournament, Request $request)
    {
        $rules = [
            'team_name'          => 'required',
        ];
        $messages = [
            'team_name.required'         => 'Team Name is required',
        ];
        $this->validate($request, $rules, $messages);
        if (!$tournament->signupsOpen()) {
            Session::flash('alert-danger', 'Signups not permitted at this time.');
            return Redirect::back();
        }

        if ($tournament->event_id && !$tournament->event->getEventParticipant()) {
            Session::flash('alert-danger', 'You are not signed in to this event.');
            return Redirect::back();
        }

        if ($tournament->hasEvent() && !$tournament->event->getEventParticipant()) {
            Session::flash('alert-danger', 'You are not signed in to this event.');
            return Redirect::back();
        }

        if ($tournament->isUserSignedUp(Auth::user())) {
            Session::flash('alert-danger', 'You are already signed up to this tournament.');
            return Redirect::back();
        }

        $tournamentTeam                 = new TournamentTeam();
        $tournamentTeam->tournament_id  = $tournament->id;
        $tournamentTeam->name           = $request->team_name;
        $tournamentTeam->password       = null;
        if ($request->team_password != "") {
            $tournamentTeam->password = $request->team_password;
        }
        if (!$tournamentTeam->save()) {
            Session::flash('alert-danger', 'Cannot add Team. Please try again.');
            return Redirect::back();
        }

        $tournamentParticipant                      = new TournamentParticipant();
        $tournamentParticipant->user_id             = Auth::id();
        $tournamentParticipant->tournament_id       = $tournament->id;
        $tournamentParticipant->tournament_team_id  = $tournamentTeam->id;
        if (!$tournamentParticipant->save()) {
            Session::flash('alert-danger', 'Cannot add participant. Please try again.');
            return Redirect::back();
        }

        Session::flash('alert-success', 'Team Successfully Created!');
        return Redirect::back();
    }

    /**
     * Unregister from Tournament
     * @param  Tournament $tournament
     * @param  Request         $request
     * @return Redirect
     */
    public function unregister(Tournament $tournament, Request $request)
    {
        if (!$tournamentParticipant = $tournament->getParticipantByUser(Auth::user())) {
            Session::flash('alert-danger', 'You are not signed up.');
            return Redirect::back();
        }

        if (!$tournamentParticipant->delete()) {
            Session::flash('alert-danger', 'Cannot remove. Please try again.');
            return Redirect::back();
        }

        Session::flash('alert-success', 'You have been successfully removed from the Tournament.');
        return Redirect::back();
    }
}