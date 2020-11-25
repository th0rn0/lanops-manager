<?php

namespace App\Http\Controllers\Admin;

use DB;
use Auth;
use Session;
use Storage;
use Input;
use Image;
use File;
use Helpers;

use App\MatchMaking;
use App\MatchMakingServer;
use App\Game;
use App\GameServer;
use App\GameServerCommand;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;


class MatchMakingServerController extends Controller
{

    /**
     * Store MatchMakingServer to Database
     * @param  MatchMaking $match
     * @param  Request $request
     * @return Redirect
     */
    public function store(MatchMaking $match, Request $request)
    {
        $matchMakingServer                 = new MatchMakingServer();
        $matchMakingServer->match_id        = $match->id;
        $matchMakingServer->game_server_id = $request->gameServer;

        if (!$matchMakingServer->save()) {
            Session::flash('alert-danger', 'Could not save matchMakingServer!');
            return Redirect::back();
        }

        if ($match->status == "PENDING")
        {

            if (isset($match->game) && $match->game->matchmaking_autoapi)
            {
                if (!$match->setStatus('WAITFORPLAYERS')) {
                    Session::flash('alert-danger', 'Cannot start Match!');
                    return Redirect::back();
                }
        
                Session::flash('alert-success', 'Match Started!');
                return Redirect::back();
            }
            else
            {
                if (!$match->setStatus('LIVE')) {
                    Session::flash('alert-danger', 'Cannot start Match!');
                    return Redirect::back();
                }
        
                Session::flash('alert-success', 'Match Started!');
                return Redirect::back();
            }

        }

        Session::flash('alert-success', 'Successfully saved matchMakingServer!');
        return Redirect::back();
    }


    /**
     * update MatchMakingServer to Database
     * @param  MatchMaking $match
     * @param  Request $request
     * @return Redirect
     */
    public function update(MatchMaking $match, Request $request)
    {

        $matchMakingServer = MatchMakingServer::where(['match_id' => $match->id])->first();

        if (!isset($matchMakingServer))
        {
            Session::flash('alert-danger', 'could not find the corresponding server entry!');
            return Redirect::back();
        }

        $matchMakingServer->game_server_id = $request->gameServer;

        if (!$matchMakingServer->save()) {
            Session::flash('alert-danger', 'Could not save MatchMakingServer!');
            return Redirect::back();
        }

        Session::flash('alert-success', 'Successfully saved MatchMakingServer!');
        return Redirect::back();

   }

    /**
     * Store MatchMakingServer to Database
     * @param  MatchMaking $match
     * @param  Request $request
     * @return Redirect
     */
    public function destroy(MatchMaking $match, Request $request)
    {
        $matchMakingServer = MatchMakingServer::where(['_match_id' => $match->id])->first();

        if (!isset($matchMakingServer))
        {
            Session::flash('alert-danger', 'could not find the corresponding MatchMakingServer entry!');
            return Redirect::back();
        }

        if (!$matchMakingServer->delete()) {
            Session::flash('alert-danger', 'Cannot delete MatchMakingServer!');
            return Redirect::back();
        }

        Session::flash('alert-success', 'Successfully deleted MatchMakingServer!');
        return Redirect::back();
    }
}
