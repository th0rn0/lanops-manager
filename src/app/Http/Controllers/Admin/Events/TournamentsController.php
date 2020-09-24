<?php

namespace App\Http\Controllers\Admin\Events;

use DB;
use Auth;
use Session;
use DateTime;
use Storage;

use App\User;
use App\Event;
use App\Game;
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
     * Show Tournaments Index Page
     * @param  Event  $event
     * @return View
     */
    public function index(Event $event)
    {
        return view('admin.events.tournaments.index')
            ->withEvent($event)
            ->withTournaments($event->tournaments()->paginate(10))
        ;
    }

    /**
     * Show Tournaments Page
     * @param  Event            $event
     * @param  EventTournament  $tournament
     * @return View
     */
    public function show(Event $event, EventTournament $tournament)
    {
        return view('admin.events.tournaments.show')
            ->withEvent($event)
            ->withTournament($tournament);
    }
   
    /**
     * Store Tournament to Database
     * @param  Event   $event
     * @param  Request $request
     * @return Redirect
     */
    public function store(Event $event, Request $request)
    {
        if (config('challonge.api_key') == null) {
            Session::flash('message', 'Cannot create Tournament! Please enter the Challonge API Key in Settings');
            return Redirect::back();
        }
        $rules = [
            'name'          => 'required',
            'format'        => 'required|in:single elimination,double elimination,round robin,list',
            'team_size'     => 'required|in:1v1,2v2,3v3,4v4,5v5,6v6',
            'description'   => 'required',
            'rules'         => 'required',
            'image'         => 'image',
        ];
        $messages = [
            'name.required'         => 'Tournament name is required',
            'format.required'       => 'Format is required',
            'format.in'             => 'Single Elimation, Double Elimination, List or Round Robin only',
            'team_size.required'    => 'Team size is required',
            'team_size.in'          => 'Team Size must be in format 1v1, 2v2, 3v3 etc',
            'description.required'  => 'Description is required',
            'rules.required'        => 'Rules are required',
            'image.image'           => 'Tournament image must be a Image'
        ];
        $this->validate($request, $rules, $messages);

        $game_id = null;
        if (isset($request->game_id)) {
            if (Game::where('id', $request->game_id)->first()) {
                $game_id = $request->game_id;
            }
        }
        $tournament                             = new EventTournament();
        $tournament->event_id                   = $event->id;
        $tournament->challonge_tournament_url   = str_random(16);
        $tournament->name                       = $request->name;
        $tournament->game_id                    = $game_id;
        $tournament->format                     = $request->format;
        $tournament->team_size                  = $request->team_size;
        $tournament->description                = $request->description;
        $tournament->rules                      = $request->rules;
        $tournament->allow_bronze               = ($request->allow_bronze ? true : false);
        $tournament->allow_player_teams         = ($request->allow_player_teams ? true : false);
        $tournament->status                     = 'DRAFT';

        if (!$tournament->save()) {
            Session::flash('message', 'Cannot create Tournament!');
            return Redirect::back();
        }

        Session::flash('message', 'Successfully created Tournament!');
        return Redirect::back();
    }

    /**
     * Update Tournament
     * @param  Event           $event
     * @param  EventTournament $tournament
     * @param  Request         $request
     * @return Redirect
     */
    public function update(Event $event, EventTournament $tournament, Request $request)
    {
        $rules = [
            'name'          => 'filled',
            'status'        => 'in:DRAFT,OPEN,CLOSED,LIVE,COMPLETE',
            'description'   => 'filled',
            'rules'         => 'filled',
        ];
        $messages = [
            'name.filled'           => 'Tournament name cannot be empty',
            'status.in'             => 'Status must be DRAFT, OPEN, CLOSED, LIVE or COMPLETE',
            'description.filled'    => 'Description cannot be empty',
            'rules.filled'          => 'Rules cannot be empty',
        ];
        $this->validate($request, $rules, $messages);

        if (isset($request->status) && $request->status != $tournament->status) {
            if (!$tournament->setStatus($request->status)) {
                Session::flash('alert-danger', 'Tournament status cannot be updated!');
                return Redirect::back();
            }
        }

        $tournament->name           = $request->name;
        $tournament->description    = $request->description;
        $tournament->rules          = $request->rules;
        $disallowed_array = ['OPEN', 'CLOSED', 'LIVE', 'COMPLETED'];
        if (!in_array($tournament->status, $disallowed_array)) {
            $game_id = null;
            if (isset($request->game_id)) {
                if (Game::where('id', $request->game_id)->first()) {
                    $game_id = $request->game_id;
                }
            }
            $tournament->game_id                    = $game_id;
        }

        if (!$tournament->save()) {
            session::flash('alert-danger', 'Cannot update Tournament!');
            return Redirect::back();
        }

        session::flash('alert-success', 'Successfully updated Tournament!');
        return Redirect::back();
    }

    /**
     * Delete Tournament from Database
     * @param  Event           $event
     * @param  EventTournament $tournament
     * @return Redirect
     */
    public function destroy(Event $event, EventTournament $tournament)
    {
        if (!$tournament->delete()) {
            Session::flash('alert-danger', 'Cannot delete Tournament!');
            return Redirect::to('admin/events/' . $event->slug . '/tournaments');
        }

        Session::flash('alert-success', 'Successfully deleted Tournament!');
        return Redirect::to('admin/events/' . $event->slug . '/tournaments');
    }

    /**
     * Start Tournament
     * @param  Event           $event
     * @param  EventTournament $tournament
     * @return Redirect
     */
    public function start(Event $event, EventTournament $tournament)
    {
        if ($tournament->tournamentParticipants->count() < 2) {
            Session::flash('alert-danger', 'Tournament doesnt have enough participants');
            return Redirect::back();
        }

        if ($tournament->status == 'LIVE' || $tournament->status == 'COMPLETED') {
            Session::flash('alert-danger', 'Tournament is already live or completed');
            return Redirect::back();
        }

        if (!$tournament->tournamentTeams->isEmpty()) {
            foreach ($tournament->tournamentTeams as $team) {
                if ($team->tournamentParticipants->isEmpty()) {
                    if (!$team->delete()) {
                        Session::flash('message', 'Error connecting to Challonge!');
                        return Redirect::to('admin/events/' . $event->slug . '/tournaments');
                    }
                }
            }
        }

        if (!$tournament->setStatus('LIVE')) {
            Session::flash('alert-danger', 'Cannot start Tournament!');
            return Redirect::back();
        }

        Session::flash('alert-success', 'Tournament Started!');
        return Redirect::back();
    }

    /**
     * Finalize Tournament
     * @param  Event           $event
     * @param  EventTournament $tournament
     * @return Redirect
     */
    public function finalize(Event $event, EventTournament $tournament)
    {
        if (!$tournament->setStatus('COMPLETE')) {
            Session::flash('alert-danger', 'Cannot finalize. Tournament is still live!');
            return Redirect::back();
        }
        Session::flash('alert-success', 'Tournament Finalized!');
        return Redirect::back();
    }

    /**
     * Update Participant Team
     * @param  Event                      $event
     * @param  EventTournament            $tournament
     * @param  EventTournamentParticipant $participant
     * @param  Request                    $request
     * @return Redirect
     */
    public function updateParticipantTeam(
        Event $event,
        EventTournament $tournament,
        EventTournamentParticipant $participant,
        Request $request
    ) {
        $rules = [
            'event_tournament_team_id'  => 'required'
        ];
        $messages = [
            'event_tournament_team_id|required' => 'A Team ID is required.'
        ];
        $this->validate($request, $rules, $messages);

        $participant->event_tournament_team_id = $request->event_tournament_team_id;
        
        if (!$participant->save()) {
            Session::flash('alert-danger', 'Cannot update Participant!');
            return Redirect::back();
        }

        Session::flash('alert-success', 'Successfully updated Participant!');
        return Redirect::back();
    }

    /**
     * Unregister Participant from Tournament
     * @param  Event           $event
     * @param  EventTournament $tournament
     * @param  Request         $request
     * @return Redirect
     */
    public function unregisterParticipant(Event $event, EventTournament $tournament, Request $request)
    {
        if (!$tournamentParticipant = $tournament->getParticipant($request->event_participant_id)) {
            Session::flash('alert-danger', 'Participant is not signed up.');
            return Redirect::back();
        }

        if (!$tournamentParticipant->delete()) {
            Session::flash('alert-danger', 'Cannot remove. Please try again.');
            return Redirect::back();
        }

        Session::flash('alert-success', 'Participant has been successfully removed from the Tournament.');
        return Redirect::back();
    }

    /**
     * Update Match on Challone API
     * @param  Event           $event
     * @param  EventTournament $tournament
     * @param  Request         $request
     * @return Redirect
     */
    public function updateMatch(Event $event, EventTournament $tournament, Request $request)
    {
        $rules = [
            'player1_score'         => 'required',
            'player2_score'         => 'required',
            'tournament_match_id'   => 'required',
            'player_winner_verify'  => 'in:player1,player2'
        ];
        $messages = [
            'player1_score.required'        => 'Player 1 score is required.',
            'player2_score.required'        => 'Player 2 score is required.',
            'tournament_match_id.required'  => 'Tournament match ID is required',
            'player_winner_verify.in'       => 'Player winner Verify must be player1 or player2'
        ];
        $this->validate($request, $rules, $messages);

        if (!$tournament->updateMatch(
            $request->tournament_match_id,
            $request->player1_score,
            $request->player2_score,
            $request->player_winner_verify
        )
        ) {
            Session::flash('alert-danger', 'Cannot update match scores!');
            return Redirect::back();
        }

        Session::flash('alert-success', 'Successfully updated match scores!');
        return Redirect::back();
    }

    /**
     * Legacy - Fix All tournament scores before the Tournament Localization Patch
     */
    public function fixScores()
    {
        EventTournament::getAllScoresRetroActively();
    }
}
