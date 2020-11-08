<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use Session;
use DateTime;
use Storage;

use App\User;
use App\Game;
use App\MatchMaking;
use App\MatchMakingTeam;
use App\MatchMakingTeamPlayer;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Lanops\Challonge\Models\Match;

class MatchMakingController extends Controller
{
    /**
     * Show MatchMaking Index Page
     * @return View
     */
    public function index()
    {
        return view('matchmaking.index')
            ->withMatches(MatchMaking::all()->appends(['sort' => 'created_at'])->paginate(10));
    }

    /**
     * Show Matchmaking
     * @param  Event            $event
     * @param  EventMatch  $Match
     * @return View
     */
    public function show(MatchMaking $match)
    {
        $team1 = MatchMakingTeam::where('id', $match->first_team_id)->first();
        $team2 = MatchMakingTeam::where('id', $match->second_team_id)->first();
        $team1players = MatchMakingTeamPlayer::where('matchmaking_team_id', $match->first_team_id);
        $team2players = MatchMakingTeamPlayer::where('matchmaking_team_id', $match->second_team_id);
        $availableteams = array();

        if (isset($match->firstteam->name) && $match->firstteam->name != "")
        {
            $availableteams[$match->firstteam->id] = $match->firstteam->name;
        }
        if (isset($match->secondteam->name) && $match->secondteam->name != "")
        {
            $availableteams[$match->secondteam->id] = $match->secondteam->name;
        }
        

        return view('admin.matchmaking.show')
            ->withMatch($match)
            ->withFirstTeam($team1)
            ->withFirstTeamPlayers($team1players)
            ->withSecondTeam($team2)
            ->withSecondTeamPlayers($team2players);
    }
   
    /**
     * Store Match to Database
     * @param  Request $request
     * @return Redirect
     */
    public function store(Request $request)
    {
         $rules = [
            'team1name'          => 'required',
            'team2name'          => 'required',
            'team_size'     => 'required|in:1v1,2v2,3v3,4v4,5v5,6v6',
            'ownerteam'             => 'required|in:1,2',
        ];
        $messages = [
            'team1name.required'         => 'Team 1 name is required',
            'team2name.required'         => 'Team 2 name is required',
            'team_size.required'    => 'Team size is required',
            'team_size.in'          => 'Team Size must be in format 1v1, 2v2, 3v3 etc',
            'ownerteam.required'    => 'Ownerteam is required',
            'ownerteam.in'          => 'Ownerteam must be 1 or 2',
            
        ];
        $this->validate($request, $rules, $messages);

        $game_id = null;
        if (isset($request->game_id)) {
            if (Game::where('id', $request->game_id)->first()) {
                $game_id = $request->game_id;
            }
        }

        $team1                             = new MatchMakingTeam();
        $team1->name                       = $request->team1name;
        $team1->team_size                  = $request->teamsize[0];
        if (!$team1->save()) {
            Session::flash('message', 'Cannot create Team 1!');
            return Redirect::back();
        }
        $team2                             = new MatchMakingTeam();
        $team2->name                       = $request->team2name;
        $team2->team_size                  = $request->teamsize[0];
        if (!$team2->save()) {
            Session::flash('message', 'Cannot create Team 2!');
            return Redirect::back();
        }


        $match                             = new MatchMaking();
        $match->first_team_id              = $team1->id;
        $match->second_team_id              = $team2->id;
        $match->game_id                    = $game_id;
        $match->status                     = 'DRAFT';
        $match->ispublic                     = ($request->ispublic ? true : false);
        $match->owner_id                   = Auth::id();

        if (!$match->save()) {
            Session::flash('message', 'Cannot create Match!');
            return Redirect::back();
        }


        if ($request->ownerteam == 1)
        {
            $teamid = $match->first_team_id;
        }
        if ($request->ownerteam == 2)
        {
            $teamid = $match->second_team_id;
        }


        $teamplayer                             = new MatchMakingTeamPlayer();
        $teamplayer->matchmaking_team_id                       = $teamid;
        $teamplayer->user_id                  = $match->owner_id;
        if (!$teamplayer->save()) {
            Session::flash('message', 'Cannot create Teamplayer!');
            return Redirect::back();
        }

        

        Session::flash('message', 'Successfully created Match!');
        return Redirect::back();
    }

     /**
     * add user to match and team Database
     * @param  Request $request
     * @return Redirect
     */
    public function addusertomatch(MatchMaking $match, Request $request)
    {
         $rules = [
            'teamid'          => 'required|in:1,2',
            'userid'               => 'required|exists:users,id',
        ];
        $messages = [
            'teamid.required'    => 'Team id is required',
            'teamid.in'          => 'Team id must be 1 or 2',
            'userid.required'    => 'userid is required',
            'userid.exists'          => 'userid must be a valid user id',
            
        ];
        $this->validate($request, $rules, $messages);

        if($match->status != "OPEN")
        {
            Session::flash('message', 'Cannot Join Match if its status is not open');
            return Redirect::back();   
        }

        if ($request->teamid == 1)
        {
            $teamid = $match->first_team_id;
        }
        if ($request->teamid == 2)
        {
            $teamid = $match->second_team_id;
        }


        $teamplayer                             = new MatchMakingTeamPlayer();
        $teamplayer->matchmaking_team_id                       = $teamid;
        $teamplayer->user_id                  = $request->userid;
        if (!$teamplayer->save()) {
            Session::flash('message', 'Cannot create Teamplayer!');
            return Redirect::back();
        }

        Session::flash('message', 'Successfully added Teamplayer!');
        return Redirect::back();
    }

    /**
     * removes user from match and team Database
     * @param  MatchMaking $match
     * @param  MatchMakingTeamPlayer $teamplayer
     * @return Redirect
     */
    public function deleteuserfrommatch(MatchMaking $match, MatchMakingTeamPlayer $teamplayer)
    {
    
        if($match->status != "OPEN")
        {
            Session::flash('message', 'Cannot leave Match if its status is not open');
            return Redirect::back();   
        }

        if (!$teamplayer->delete()) {
            Session::flash('alert-danger', 'Cannot delete Teamplayer!');
            return Redirect::to('admin/matchmaking/'.$match->slug);
        }

        Session::flash('alert-success', 'Successfully deleted Teamplayer!');
        return Redirect::to('admin/matchmaking/.$match->slug');


    }

 
    

    /**
     * Delete Match from Database
     * @param  MatchMaking $match
     * @return Redirect
     */
    public function destroy(MatchMaking $match)
    {
        $currentuser                  = Auth::id();

        if ($match->owner_id != $currentuser)
        {
            Session::flash('alert-danger', 'Cannot delete Match because you are not the owner!');
            return Redirect::back();
        }
       
        if (!$match->delete()) {
        Session::flash('alert-danger', 'Cannot delete Match!');
        return Redirect::back();
        }

        Session::flash('alert-success', 'Successfully deleted Match!');
        return Redirect::back();
    }

    /**
     * Start Match
     * @param  MatchMaking $match
     * @return Redirect
     */
    public function start(MatchMaking $match)
    {
        $currentuser                  = Auth::id();

        if ($match->owner_id != $currentuser)
        {
            Session::flash('alert-danger', 'Cannot start Match because you are not the owner!');
            return Redirect::back();
        }
       
        if ($match->teams->players->count() < $match->teams->team_size) {
            Session::flash('alert-danger', 'Match doesnt have enough participants');
            return Redirect::back();
        }

        if ($match->status == 'LIVE' || $match->status == 'COMPLETED') {
            Session::flash('alert-danger', 'Match is already live or completed');
            return Redirect::back();
        }


        if (!$match->setStatus('LIVE')) {
            Session::flash('alert-danger', 'Cannot start Match!');
            return Redirect::back();
        }

        Session::flash('alert-success', 'Match Started!');
        return Redirect::back();
    }

       /**
     * open Match
     * @param  MatchMaking $match
     * @return Redirect
     */
    public function open(MatchMaking $match)
    {
        $currentuser                  = Auth::id();

        if ($match->owner_id != $currentuser)
        {
            Session::flash('alert-danger', 'Cannot start Match because you are not the owner!');
            return Redirect::back();
        }
       
        if ($match->teams->players->count() < $match->teams->team_size) {
            Session::flash('alert-danger', 'Match doesnt have enough participants');
            return Redirect::back();
        }

        if ($match->status == 'OPEN' || $match->status == 'LIVE' || $match->status == 'COMPLETED') {
            Session::flash('alert-danger', 'Match is already open/ live or completed');
            return Redirect::back();
        }


        if (!$match->setStatus('OPEN')) {
            Session::flash('alert-danger', 'Cannot start Match!');
            return Redirect::back();
        }

        Session::flash('alert-success', 'Match Started!');
        return Redirect::back();
    }

    /**
     * Finalize Match
     * @param  MatchMaking $match
     * @return Redirect
     */
    public function finalize(MatchMaking $match)
    {
        $currentuser                  = Auth::id();

        if ($match->owner_id != $currentuser)
        {
            Session::flash('alert-danger', 'Cannot finalize Match because you are not the owner!');
            return Redirect::back();
        }

        if (!$match->setStatus('COMPLETE')) {
            Session::flash('alert-danger', 'Cannot finalize. Match is still live!');
            return Redirect::back();
        }
        Session::flash('alert-success', 'Match Finalized!');
        return Redirect::back();
    }

       

    
}
