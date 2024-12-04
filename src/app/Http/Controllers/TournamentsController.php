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
     * Show Tournaments Page
     * @param  Event            $event
     * @param  EventTournament  $tournament
     * @param  Request          $request
     * @return View
     */
    // public function show(Event $event, EventTournament $tournament, Request $request)
    // {
    //     if (!$user = Auth::user()) {
    //         Redirect::to('/');
    //     }
    //     $user->setActiveEventParticipant($event->id);
    //     if (!isset($user->active_event_participant)) {
    //         Session::flash('alert-danger', 'Please sign in with one of our Admins.');
    //         return Redirect::to('/')->withErrors('Please sign in with one of our Admins.');
    //     }
    //     return view('events.tournaments.show')
    //         ->withTournament($tournament)
    //         ->withEvent($event)
    //         ->withUser($user)
    //     ;
    // }
    
    /**
     * Register to Tournament
     * @param  Tournament       $tournament
     * @param  Request          $request
     * @return [type]
     */
    public function register(Tournament $tournament, Request $request)
    {
        if ($tournament->status != 'OPEN') {
            Session::flash('alert-danger', 'Signups not permitted at this time.');
            return Redirect::back();
        }

        if ($tournament->event_id && !$tournament->event->eventParticipants()->where('id', $request->user_id)->get() > 0) {
            Session::flash('alert-danger', 'You are not signed in to this event.');
            return Redirect::back();
        }
        if ($tournament->isUserSignedUp(Auth::user())) {
            Session::flash('alert-danger', 'You are already signed up to this tournament.');
            return Redirect::back();
        }
        // if (isset($request->event_tournament_team_id) &&
        //     $tournamentTeam = $tournament->tournamentTeams()->where('id', $request->event_tournament_team_id)->first()
        // ) {
        //     if ($tournamentTeam->tournamentParticipants->count() == substr($tournament->team_size, 0, 1)) {
        //         Session::flash('alert-danger', 'This team is full.');
        //         return Redirect::back();
        //     }
        // }
        $tournamentParticipant                  = new TournamentParticipant();
        $tournamentParticipant->user_id         = Auth::id();
        $tournamentParticipant->tournament_id   = $tournament->id;
        // $tournamentParticipant->event_tournament_team_id    = @$request->event_tournament_team_id;
        if (!$tournamentParticipant->save()) {
            Session::flash('alert-danger', 'Cannot add participant. Please try again.');
            return Redirect::back();
        }
        Session::flash('alert-success', 'Successfully Registered!');
        return Redirect::back();
    }
    // /**
    //  * Register Team to Tournament
    //  * @param  Event           $event
    //  * @param  EventTournament $tournament
    //  * @param  Request         $request
    //  * @return Redirect
    //  */
    // public function registerTeam(Event $event, EventTournament $tournament, Request $request)
    // {
    //     if ($tournament->status != 'OPEN') {
    //         Session::flash('alert-danger', 'Signups not permitted at this time.');
    //         return Redirect::back();
    //     }
     
    //     if (!$tournament->event->eventParticipants()->where('id', $request->event_participant_id)->first()) {
    //         Session::flash('alert-danger', 'You are not signed in to this event.');
    //         return Redirect::back();
    //     }
    //     if ($tournament->getParticipant($request->event_participant_id)) {
    //         Session::flash('alert-danger', 'You are already signed up to this tournament.');
    //         return Redirect::back();
    //     }
    //     $tournamentTeam                         = new EventTournamentTeam();
    //     $tournamentTeam->event_tournament_id    = $tournament->id;
    //     $tournamentTeam->name                   = $request->team_name;
    //     if (!$tournamentTeam->save()) {
    //         Session::flash('alert-danger', 'Cannot add Team. Please try again.');
    //         return Redirect::back();
    //     }
    //     // TODO - Refactor
    //     $tournamentParticipant                              = new EventTournamentParticipant();
    //     $tournamentParticipant->event_participant_id        = $request->event_participant_id;
    //     $tournamentParticipant->event_tournament_id         = $tournament->id;
    //     $tournamentParticipant->event_tournament_team_id    = $tournamentTeam->id;
    //     if (!$tournamentParticipant->save()) {
    //         Session::flash('alert-danger', 'Cannot add participant. Please try again.');
    //         return Redirect::back();
    //     }
    //     Session::flash('alert-success', 'Team Successfully Created!');
    //     return Redirect::back();
    // }
    // /**
    //  * Register Pug to Tournament
    //  * @param  Event           $event
    //  * @param  EventTournament $tournament
    //  * @param  Request         $request
    //  * @return Redirect
    //  */
    // public function registerPug(Event $event, EventTournament $tournament, Request $request)
    // {
    //     if ($tournament->status != 'OPEN') {
    //         Session::flash('alert-danger', 'Signups not permitted at this time.');
    //         return Redirect::back();
    //     }
     
    //     if (!$tournament->event->eventParticipants()->where('id', $request->event_participant_id)->first()) {
    //         Session::flash('alert-danger', 'You are not signed in to this event.');
    //         return Redirect::back();
    //     }
    //     if ($tournament->getParticipant($request->event_participant_id)) {
    //         Session::flash('alert-danger', 'You are already signed up to this tournament.');
    //         return Redirect::back();
    //     }
    //     $tournamentParticipant                          = new EventTournamentParticipant();
    //     $tournamentParticipant->event_participant_id    = $request->event_participant_id;
    //     $tournamentParticipant->event_tournament_id     = $tournament->id;
    //     $tournamentParticipant->pug                     = true;
    //     if (!$tournamentParticipant->save()) {
    //         Session::flash('alert-danger', 'Cannot add PUG. Please try again.');
    //         return Redirect::back();
    //     }
    //     Session::flash('alert-success', 'Successfully Registered!');
    //     return Redirect::back();
    // }

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