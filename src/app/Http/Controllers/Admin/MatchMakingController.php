<?php

namespace App\Http\Controllers\Admin;

use DB;
use Auth;
use Session;
use DateTime;
use Storage;
use Settings;

use App\User;
use App\Event;
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
        $allusers = User::all();
        $matches = MatchMaking::all()->append(['sort' => 'created_at']);
        $selectallusers = array();

        foreach($allusers as $user) {
            $selectallusers[$user->id] = $user->username;
        }
        return view('admin.matchmaking.index')
            ->withMatches($matches)
            ->withisMatchMakingEnabled(Settings::isMatchMakingEnabled())
            ->withUsers($selectallusers);
    }

    /**
     * Show Matchmaking
     * @param MatchMaking $match
     * @return View
     */
    public function show(MatchMaking $match)
    {     
        $allusers = User::all();
        $selectallusers = array();
        $availableteams = array();
        $availableusers = array();

        

        foreach($allusers as $user) {
            $selectallusers[$user->id] = $user->username;
        }

        foreach($allusers as $user) {
            $alreadyjoined = false;
            if (isset($match->firstteam->name) && $match->firstteam->name != "") 
            {
                foreach ($match->firstteam->players as $matchuser) {
                    if ($user->id == $matchuser->user_id) {
                        $alreadyjoined = true;
                    }
                }
            }
            if(isset($match->secondteam->name) && $match->secondteam->name != "")
            {
                foreach($match->secondteam->players as $matchuser)
                {
                    if ($user->id == $matchuser->user_id)
                    {
                        $alreadyjoined = true;
                    }
                }
            }

            
            if (!$alreadyjoined)
            {
                $availableusers[$user->id] = $user->username;
            }
        }


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
            ->withAvailableTeams($availableteams)
            ->withAvailableUsers($availableusers)
            ->withUsers($selectallusers);
            
    }
   
    /**
     * Store Match to Database
     * @param  Request $request
     * @return Redirect
     */
    public function store(Request $request)
    {
        $rules = [
            'team_size'     => 'required|in:1v1,2v2,3v3,4v4,5v5,6v6',
            'ownerid'               => 'required|exists:users,id',
        ];
        $messages = [
          
            'team_size.required'    => 'Team size is required',
            'ownerid.required'    => 'ownerid is required',
            'ownerid.exists'          => 'ownerid must be a valid user id',
            
        ];
        $this->validate($request, $rules, $messages);

        if ($request->team1name == "" && $request->team2name == "")
        {
            Session::flash('alert-danger', 'at least one Team is required');
            return Redirect::back();
        }
        if (isset($request->team1name) && isset($request->team2name) && $request->team1name != "" && $request->team2name != "" && $request->team1owner == $request->team2owner)
        {
            Session::flash('alert-danger', 'Owner for Team 1 and Team2 must be different!');
            return Redirect::back();
        }

        $match                             = new MatchMaking();

        $game_id = null;
        if (isset($request->game_id)) {
            if (Game::where('id', $request->game_id)->first()) {
                $game_id = $request->game_id;
            }
        }

        if (isset($request->team1name) && $request->team1name != "")
        {
            if (!isset($request->team1owner))
            {
                Session::flash('alert-danger', 'Owner for Team 1 is missing!');
                return Redirect::back();
            }
        
            
           
            if (!User::where('id', $request->team1owner)->first()) {
                Session::flash('alert-danger', 'Owner for Team 1 is not a valid user!');
                return Redirect::back();
            }
            

            $team1                             = new MatchMakingTeam();
            $team1->name                       = $request->team1name;
            $team1->team_owner_id                 = $request->team1owner;
            $team1->team_invite_tag             = base_convert(microtime(false), 10, 36);
            if (!$team1->save()) {
                Session::flash('alert-danger', 'Cannot create Team 1!');
                return Redirect::back();
            }
            $match->first_team_id              = $team1->id;
        }

        if (isset($request->team2name) && $request->team2name != "")
        {
            if (!isset($request->team2owner))
            {
                Session::flash('alert-danger', 'Owner for Team 2 is missing!');
                return Redirect::back();
            }

           
            if (!User::where('id', $request->team2owner)->first()) {
                Session::flash('alert-danger', 'Owner for Team 2 is not a valid user!');
                return Redirect::back();
            }
            
            $team2                             = new MatchMakingTeam();
            $team2->name                       = $request->team2name;
            $team2->team_owner_id                 = $request->team2owner;
            $team2->team_invite_tag             = base_convert(microtime(false), 10, 36);
            if (!$team2->save()) {
                Session::flash('alert-danger', 'Cannot create Team 2!');
                return Redirect::back();
            }
            $match->second_team_id              = $team2->id;
        }
        

        
      
        
        $match->game_id                    = $game_id;
        $match->status                     = 'DRAFT';
        $match->ispublic                     = ($request->ispublic ? true : false);
        $match->team_size                  = $request->team_size[0];
        $match->owner_id                   = $request->ownerid;
        $match->invite_tag                 = base_convert(microtime(false), 10, 36);




        if (isset($match->first_team_id))
        {
            $teamplayerone                             = new MatchMakingTeamPlayer();
            $teamplayerone->matchmaking_team_id                       = $match->first_team_id;
            $teamplayerone->user_id                  = $request->team1owner;
            if (!$teamplayerone->save()) {
                Session::flash('alert-danger', 'Cannot create Teamplayer for Team 1 Owner!');
                return Redirect::back();
            }
        }
        
        if (isset($match->second_team_id))
        {
            $teamplayertwo                             = new MatchMakingTeamPlayer();
            $teamplayertwo->matchmaking_team_id                       = $match->second_team_id;
            $teamplayertwo->user_id                  = $request->team2owner;
            if (!$teamplayertwo->save()) {
                Session::flash('alert-danger', 'Cannot create Teamplayer for Team 2 Owner!');
                return Redirect::back();
            }
        }

        if (!$match->save()) {
            Session::flash('alert-danger', 'Cannot create Match!');
            return Redirect::back();
        }
        

        Session::flash('alert-success', 'Successfully created Match!');
        return Redirect::back();
    }

      /**
     * Store Match to Database
     * @param MatchMaking $match
     * @param  Request $request
     * @return Redirect
     */
    public function update(MatchMaking $match, Request $request)
    {
        $rules = [
            'team_size'     => 'required|in:1v1,2v2,3v3,4v4,5v5,6v6',
            'ownerid'               => 'required|exists:users,id',
        ];
        $messages = [
          
            'team_size.required'    => 'Team size is required',
            'ownerid.required'    => 'ownerid is required',
            'ownerid.exists'          => 'ownerid must be a valid user id',
            
        ];
     
        $this->validate($request, $rules, $messages);

        $game_id = null;
        if (isset($request->game_id)) {
            if (Game::where('id', $request->game_id)->first()) {
                $game_id = $request->game_id;
            }
        }
        
        $match->game_id                    = $game_id;
        $match->ispublic                   = ($request->ispublic ? true : false);
        $match->team_size                  = $request->team_size[0];
        $match->owner_id                   = $request->ownerid;

        if (!$match->save()) {
            Session::flash('alert-danger', 'Cannot update Match!');
            return Redirect::back();
        }
        

        Session::flash('alert-success', 'Successfully updated Match!');
        return Redirect::back();

    }

    /**
     * add second team to match
     * @param MatchMaking $match
     * @param  Request $request
     * @return Redirect
     */
    public function addsecondteam(MatchMaking $match, Request $request)
    {
         $rules = [
            'teamname'          => 'required',
            'teamowner'               => 'required|exists:users,id',
        ];
        $messages = [
            'teamname.required'    => 'Team name is required',
            'teamowner.required'    => 'userid is required',
            'teamowner.exists'          => 'userid must be a valid user id',
            
        ];
        $this->validate($request, $rules, $messages);

        if ($match->first_team_id == null)
        {
            if ($request->teamowner == $match->secondteam->team_owner_id)
            {
                Session::flash('alert-danger', 'Owner for Team 1 and Team2 must be different!');
                return Redirect::back();
            }
        }
        if ($match->second_team_id == null)
        {
            if ($request->teamowner == $match->firstteam->team_owner_id)
            {
                Session::flash('alert-danger', 'Owner for Team 1 and Team2 must be different!');
                return Redirect::back();
            }
        }


        $team                             = new MatchMakingTeam();
        $team->name                       = $request->teamname;
        $team->team_owner_id                 = $request->teamowner;
        $team->team_invite_tag             = base_convert(microtime(false), 10, 36);

        if (!$team->save()) {
            Session::flash('alert-danger', 'Cannot create Team !');
            return Redirect::back();
        }

        $teamplayertwo                           = new MatchMakingTeamPlayer();
        $teamplayertwo->matchmaking_team_id      = $team->id;
        $teamplayertwo->user_id                  = $request->teamowner;
        if (!$teamplayertwo->save()) {
            Session::flash('alert-danger', 'Cannot create Teamplayer for Team 2 Owner!');
            return Redirect::back();
        }

        if ($match->first_team_id == null)
        {
            $match->first_team_id              = $team->id;
        }
        if ($match->second_team_id == null)
        {
            $match->second_team_id              = $team->id;
        }

        if (!$match->save()) {
            Session::flash('alert-danger', 'Cannot save Match!');
            return Redirect::back();
        }


        Session::flash('alert-success', 'Successfully added second team!');
        return Redirect::back();
    }

    /**
     * update team
     * @param MatchMaking $match
     * @param MatchMakingTeam $team
     * @param  Request $request
     * @return Redirect
     */
    public function updateteam(MatchMaking $match, MatchMakingTeam $team,  Request $request)
    {
         $rules = [
            'teamname'          => 'required',
            'teamowner'               => 'required|exists:users,id',
        ];
        $messages = [
            'teamname.required'    => 'Team name is required',
            'teamowner.required'    => 'userid is required',
            'teamowner.exists'          => 'userid must be a valid user id',
            
        ];
        $this->validate($request, $rules, $messages);

        if ($team->team_owner_id != $request->teamowner)
        {
            if ($team->team_owner_id == $match->firstteam->team_owner_id)
            {
                if ($request->teamowner == $match->secondteam->team_owner_id)
                {
                    Session::flash('alert-danger', 'Owner for Team 1 and Team2 must be different!');
                    return Redirect::back();
                }
            }
            if ($team->team_owner_id == $match->secondteam->team_owner_id)
            {
                if ($request->teamowner == $match->firstteam->team_owner_id)
                {
                    Session::flash('alert-danger', 'Owner for Team 1 and Team2 must be different!');
                    return Redirect::back();
                }
            }

            
            if (!MatchMakingTeamPlayer::where(['user_id' => $team->team_owner_id, 'matchmaking_team_id' => $team->id])->delete()) {
                Session::flash('alert-danger', 'Cannot delete old owner from team!');
                return Redirect::back();
            }

            $teamplayertwo                           = new MatchMakingTeamPlayer();
            $teamplayertwo->matchmaking_team_id      = $team->id;
            $teamplayertwo->user_id                  = $request->teamowner;
            if (!$teamplayertwo->save()) {
                Session::flash('alert-danger', 'Cannot create Teamplayer for Team Owner!');
                return Redirect::back();
            }
            
        }


        $team->name                       = $request->teamname;
        $team->team_owner_id                 = $request->teamowner;
        if (!$team->save()) {
            Session::flash('alert-danger', 'Cannot save Team !');
            return Redirect::back();
        }




        Session::flash('alert-success', 'Successfully updated Team!');
        return Redirect::back();
    }
 
    /**
     * add user to match and team Database
     * @param MatchMaking $match
     * @param  Request $request
     * @return Redirect
     */
    public function addusertomatch(MatchMaking $match, Request $request)
    {
         $rules = [
            'teamid'          => 'required|exists:matchmaking_teams,id',
            'userid'               => 'required|exists:users,id',
        ];
        $messages = [
            'teamid.required'    => 'Team id is required',
            'teamid.in'          => 'Team id must be a valid matchmaking team id',
            'userid.required'    => 'userid is required',
            'userid.exists'          => 'userid must be a valid user id',
            
        ];
        $this->validate($request, $rules, $messages);

        if ($match->firstteam->id == $request->teamid)
        {
            if ($match->firstteam->players->count() >= $match->team_size)
            {
                Session::flash('alert-danger', 'Team is already full!');
                return Redirect::back();
            }
        }
        if ($match->secondteam->id == $request->teamid)
        {
            if ($match->secondteam->players->count() >= $match->team_size)
            {
                Session::flash('alert-danger', 'Team is already full!');
                return Redirect::back();
            }
        }

   

        $teamplayer                             = new MatchMakingTeamPlayer();
        $teamplayer->matchmaking_team_id                       = $request->teamid;
        $teamplayer->user_id                  = $request->userid;
        if (!$teamplayer->save()) {
            Session::flash('alert-danger', 'Cannot create Teamplayer!');
            return Redirect::back();
        }

        Session::flash('alert-success', 'Successfully added Teamplayer!');
        return Redirect::back();
    }

    /**
     * removes user from match and team Database
     * @param  MatchMaking $match
     * @return Redirect
     */
    public function deleteuserfrommatch(MatchMaking $match, Request $request)
    {
        $rules = [
            'userid'               => 'required|exists:matchmaking_team_players,user_id',
        ];
        $messages = [
            'userid.required'    => 'userid is required',
            'userid.exists'          => 'userid must be a valid user id and registered for the match',
            
        ];
        $this->validate($request, $rules, $messages);

        $deleted = false;
        if (MatchMakingTeamPlayer::where(['user_id' => $request->userid, 'matchmaking_team_id' => $match->firstteam->id])->count() >= 1)
        {
            if (!MatchMakingTeamPlayer::where(['user_id' => $request->userid, 'matchmaking_team_id' => $match->firstteam->id])->delete()) {
                Session::flash('alert-danger', 'Cannot delete Teamplayer!');
                return Redirect::to('admin/matchmaking/'.$match->id);
            }
            $deleted = true;
        }

        if (MatchMakingTeamPlayer::where(['user_id' => $request->userid, 'matchmaking_team_id' => $match->secondteam->id])->count() >= 1)
        {
            if (!MatchMakingTeamPlayer::where(['user_id' => $request->userid, 'matchmaking_team_id' => $match->secondteam->id])->delete()) {
                Session::flash('alert-danger', 'Cannot delete Teamplayer!');
                return Redirect::to('admin/matchmaking/'.$match->id);
            }
            $deleted = true;
        }

        if ($deleted)
        {
            Session::flash('alert-success', 'Successfully deleted Teamplayer!');
            return Redirect::to('admin/matchmaking/' .$match->id);
        }
        else
        {
            Session::flash('alert-danger', 'Cannot delete Teamplayer!');
            return Redirect::to('admin/matchmaking/' .$match->slug);
        }



    }
 

    /**
     * Delete Match from Database
     * @param  MatchMaking $match
     * @return Redirect
     */
    public function destroy(MatchMaking $match)
    {
        if (!$match->delete()) {
            Session::flash('alert-danger', 'Cannot delete Match!');
            return Redirect::to('admin/matchmaking/');
        }

        Session::flash('alert-success', 'Successfully deleted Match!');
        return Redirect::to('admin/matchmaking/');
    }

    /**
     * Start Match
     * @param  MatchMaking $match
     * @return Redirect
     */
    public function start(MatchMaking $match)
    {
        if (!isset($match->firstteam->name) || $match->firstteam->name == "" || !isset($match->secondteam->name) || $match->secondteam->name == "") 
        {
            Session::flash('alert-danger', 'A Team is missing!');
            return Redirect::back();
        }

            if ($match->firstteam->players->count() != $match->team_size)
            {
                Session::flash('alert-danger', 'Team '. $match->firstteam->name . 'has not enough players!');
                return Redirect::back();
            }
        
            if ($match->secondteam->players->count() != $match->team_size)
            {
                Session::flash('alert-danger', 'Team '. $match->secondteam->name . 'has not enough players!');
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
       
        if ($match->status == 'OPEN' || $match->status == 'LIVE' || $match->status == 'COMPLETED') {
            Session::flash('alert-danger', 'Match is already open/ live or completed');
            return Redirect::back();
        }


        if (!$match->setStatus('OPEN')) {
            Session::flash('alert-danger', 'Cannot open Match!');
            return Redirect::back();
        }

        Session::flash('alert-success', 'Match Opened!');
        return Redirect::back();
    }

    /**
     * Finalize Match
     * @param  MatchMaking $match
     * @param Request $request
     * @return Redirect
     */
    public function finalize(MatchMaking $match, Request $request)
    {
        $rules = [
            'team1score'          => 'required|numeric',
            'team2score'               => 'required|numeric',
        ];
        $messages = [
            'team1score.required'    => 'Team 1 score is required',
            'team1score.numeric'          => 'Team 1 score must be a numeric value!',
            'team2score.required'    => 'Team 2 score is required',
            'team2score.numeric'          => 'Team 2 score must be a numeric value!',
            
        ];
        $this->validate($request, $rules, $messages);

        $match->firstteam->team_score = $request->team1score;
        if (!$match->firstteam->save())
        {
            Session::flash('alert-danger', 'Could not save Score for Team1!');
            return Redirect::back();
        }

        $match->secondteam->team_score = $request->team2score;
        if (!$match->secondteam->save())
        {
            Session::flash('alert-danger', 'Could not save Score for Team1!');
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
