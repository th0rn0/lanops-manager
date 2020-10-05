<?php

namespace App\Http\Controllers\Admin;

use DB;
use Auth;
use Session;
use Storage;
use Image;
use File;

use App\Game;
use App\GameServer;
use App\GameServerCommand;
use App\GameServerCommandParameter;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

use xPaw\SourceQuery\SourceQuery;

class GameServerCommandParametersController extends Controller
{
    /**
     * Store GameServer to Database
     * @param  Request $request
     * @return Redirect
     */
    public function store(Game $game, Request $request)
    {
        $rules = [
            'name'              => 'required',
            'options'           => 'required'
        ];
        $messages = [
            'name.required'           => 'Parameter name is required',
            'options.required'        => 'Parameteroptions are required',
        ];

        $this->validate($request, $rules, $messages);

        $gameServerCommandParameter                 = new GameServerCommandParameter();
        $gameServerCommandParameter->name           = $request->name;
        $gameServerCommandParameter->game_id        = $game->id;
        $gameServerCommandParameter->options        = $request->options;

        if (!$gameServerCommandParameter->save()) {
            Session::flash('alert-danger', 'Could not save GameServerCommandParameter!');
            return Redirect::back();
        }

        Session::flash('alert-success', 'Successfully saved GameServerCommandParameter!');
        return Redirect::to('admin/games/' . $game->slug . '#gameservercommandparameters');
    }

    /**
     * Update Game
     * @param  GameServerCommand $gameServerCommand
     * @param  Request $request
     * @return Redirect
     */
    public function update(Game $game, GameServerCommandParameter $gameServerCommandParameter, Request $request)
    {
        $rules = [
            'name'              => 'required',
            'options'           => 'required'
        ];
        $messages = [
            'name.required'           => 'Parameter name is required',
            'options.required'        => 'Parameteroptions are required',
        ];

        $this->validate($request, $rules, $messages);

        $gameServerCommandParameter->name           = $request->name;
        $gameServerCommandParameter->options        = $request->options;

        if (!$gameServerCommandParameter->save()) {
            Session::flash('alert-danger', 'Could not save Game Server Command Parameter!');
            return Redirect::back();
        }


        Session::flash('alert-success', 'Successfully saved Game Server Command Parameter!');
        return Redirect::to('admin/games/' . $game->slug . '#gameservercommandparameters');
    }

    /**
     * Delete GameServerCommandParameter from Database
     * @param  Game  $game
     * @param  GameServerCommandParameter  $gameServerCommandParameter
     * @return Redirect
     */
    public function destroy(Game $game, GameServerCommandParameter $gameServerCommandParameter)
    {
        if (!$gameServerCommandParameter->delete()) {
            Session::flash('alert-danger', 'Cannot delete GameServerCommandParameter!');
            return Redirect::back();
        }

        Session::flash('alert-success', 'Successfully deleted GameServerCommandParameter!');
        return Redirect::to('admin/games/' . $game->slug . '#gameservercommandparameters');
    }
}
