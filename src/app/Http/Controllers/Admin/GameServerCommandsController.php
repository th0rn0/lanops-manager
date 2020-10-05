<?php

namespace App\Http\Controllers\Admin;

use DB;
use Auth;
use Session;
use Storage;
use Image;
use File;
use Helpers;

use App\Game;
use App\GameServer;
use App\GameServerCommand;
use App\GameServerCommandParameter;
use App\EventTournament;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

use xPaw\SourceQuery\SourceQuery;

class GameServerCommandsController extends Controller
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
            'command'           => 'required',
            'scope'             => 'required'
        ];
        $messages = [
            'name.required'           => 'Command name is required',
            'command.required'        => 'Command is required',
            'scope.required'          => 'Command scope is required',
        ];

        $this->validate($request, $rules, $messages);

        $gameServerCommand                 = new GameServerCommand();
        $gameServerCommand->name           = $request->name;
        $gameServerCommand->game_id        = $game->id;
        $gameServerCommand->command        = $request->command;
        $gameServerCommand->scope          = $request->scope;

        if (!$gameServerCommand->save()) {
            Session::flash('alert-danger', 'Could not save GameServerCommand!');
            return Redirect::back();
        }

        Session::flash('alert-success', 'Successfully saved GameServerCommand!');
        return Redirect::to('admin/games/' . $game->slug . '#gameserverscommands');
    }

    /**
     * Update Game
     * @param  GameServerCommand $gameServerCommand
     * @param  Request $request
     * @return Redirect
     */
    public function update(Game $game, GameServerCommand $gameServerCommand, Request $request)
    {
        $rules = [
            'name'              => 'required',
            'command'           => 'required',
            'scope'             => 'required'
        ];
        $messages = [
            'name.required'           => 'Command name is required',
            'command.required'        => 'Command is required',
            'scope.required'          => 'Command scope is required',
        ];


        $this->validate($request, $rules, $messages);

        $gameServerCommand->name           = $request->name;
        $gameServerCommand->command        = $request->command;
        $gameServerCommand->scope          = $request->scope;

        if (!$gameServerCommand->save()) {
            Session::flash('alert-danger', 'Could not save Game Server Command!');
            return Redirect::back();
        }


        Session::flash('alert-success', 'Successfully saved Game Server Command!');
        return Redirect::to('admin/games/' . $game->slug . '#gameserverscommands');
    }

    /**
     * Delete GameServerCommand from Database
     * @param  Game  $game
     * @param  GameServerCommand  $gameServerCommand
     * @return Redirect
     */
    public function destroy(Game $game, GameServerCommand $gameServerCommand)
    {
        // if ($game->eventTournaments && !$game->eventTournaments->isEmpty()) {
        //     Session::flash('alert-danger', 'Cannot delete game with tournaments!');
        //     return Redirect::back();
        // }

        if (!$gameServerCommand->delete()) {
            Session::flash('alert-danger', 'Cannot delete GameServerCommand!');
            return Redirect::back();
        }

        Session::flash('alert-success', 'Successfully deleted GameServerCommand!');
        return Redirect::back();
    }

    private function executeCommand(GameServer $gameServer, string $command)
    {
        $game = $gameServer->game;
        if ($game->gamecommandhandler == 0 || $game->gamecommandhandler == 1) {
            $Query = new SourceQuery();
            try {
                if ($game->gamecommandhandler == 0) {
                    $Query->Connect($gameServer->address, $gameServer->rcon_port, 1, SourceQuery::GOLDSOURCE);
                }
                if ($game->gamecommandhandler == 1) {
                    $Query->Connect($gameServer->address, $gameServer->rcon_port, 1, SourceQuery::SOURCE);
                }
                $Query->SetRconPassword($gameServer->rcon_password);
                $result = $Query->Rcon($command);
            } catch (Exception $e) {
                $error = $e->getMessage();
            } finally {
                $Query->Disconnect();
            }

            if (!isset($error) || $result != false) {
                Session::flash('alert-success', 'Successfully executed command "' . $command . '" with connector ' . Helpers::getGameCommandHandler()[$game->gamecommandhandler] . ' Result:' . var_export($result, true));
            } else {
                Session::flash('alert-danger', 'error while executing command "' . $command . '" with connector ' . Helpers::getGameCommandHandler()[$game->gamecommandhandler] . ' Error:' . var_export($error, true) . ' Result:' . var_export($result, true));
            }
        }
    }

    /**
     * execute gameServerCommand
     * @param  Game  $game
     * @param  GameServer  $gameServer
     * @param  Request $request
     * @return Redirect
     */
    public function executeGameServerCommand(Game $game, GameServer $gameServer, Request $request)
    {
        $rules = [
            'command'           => 'filled'
        ];
        $messages = [
            'command.required' => 'Command is required'
        ];
        $this->validate($request, $rules, $messages);

        $gameServerCommand = GameServerCommand::find($request->command);
        $availableParameters = new \stdClass();
        $availableParameters->game = $game;
        $availableParameters->gameServer = $gameServer;

        $command = Helpers::resolveServerCommandParameters($gameServerCommand->command, $request, $availableParameters);

        $this->executeCommand($gameServer, $command);

        return Redirect::back();
    }

    /**
     * execute gameServerMatchCommand
     * @param  Game  $game
     * @param  GameServer  $gameServer
     * @param  EventTournament $tournament
     * @param  Request $request
     * @return Redirect
     */
    public function executeGameServerMatchCommand(Game $game, GameServer $gameServer, EventTournament $tournament, Request $request)
    {
        $rules = [
            'command'           => 'filled'
        ];
        $messages = [
            'command.required' => 'Command is required'
        ];
        $this->validate($request, $rules, $messages);

        $challongeMatch = $tournament->getMatch($request->challonge_match_id);

        $gameServerCommand = GameServerCommand::find($request->command);
        $availableParameters = new \stdClass();
        $availableParameters->game = $game;
        $availableParameters->event = $tournament->event;
        $availableParameters->tournament = $tournament;
        $availableParameters->gameServer = $gameServer;
        $availableParameters->match = $challongeMatch;

        $command = Helpers::resolveServerCommandParameters($gameServerCommand->command, $request, $availableParameters);

        $this->executeCommand($gameServer, $command);

        return Redirect::back();
    }
}
