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
    // /**
    //  * Show Games Index Page
    //  * @return Redirect
    //  */
    // public function index()
    // {
    //     return view('admin.games.index')
    //         ->withGames(Game::paginate(20));
    // }

    // /**
    //  * Show Game Page
    //  * @return Redirect
    //  */
    // public function show(Game $game)
    // {
    //     return view('admin.games.show')
    //         ->withGame($game);
    // }

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
        return Redirect::back();
    }

    /**
     * Update Game
     * @param  GameServerCommand $gameServerCommand
     * @param  Request $request
     * @return Redirect
     */
    public function update(Game $game, GameServerCommand $gameServerCommand, Request $request)
    {
        // $rules = [
        //     'name'              => 'filled',
        //     'active'            => 'in:true,false',
        //     'image_header'      => 'image',
        //     'image_thumbnail'   => 'image',
        // ];
        // $messages = [
        //     'name.required'         => 'Game name is required',
        //     'active.filled'         => 'Active must be true or false',
        //     'image_header.image'    => 'Header image must be a Image',
        //     'image_thumbnail.image' => 'Thumbnail image must be a Image'
        // ];
        // $this->validate($request, $rules, $messages);

        // $game->name         = @$request->name;
        // $game->description  = @(trim($request->description) == '' ? null : $request->description);
        // $game->version      = @(trim($request->version) == '' ? null : $request->version);
        // $game->public       = @($request->public ? true : false);

        // if (!$game->save()) {
        //     Session::flash('alert-danger', 'Could not save Game!');
        //     return Redirect::back();
        // }

        // $destinationPath = '/storage/images/games/' . $game->slug . '/';
        
        // if ((Request::file('image_thumbnail') || Request::file('image_header')) &&
        //     !File::exists(public_path() . $destinationPath)
        // ) {
        //     File::makeDirectory(public_path() . $destinationPath, 0777, true);
        // }

        // if (Request::file('image_thumbnail')) {
        //     Storage::delete($game->image_thumbnail_path);
        //     $imageName  = 'thumbnail.' . Request::file('image_thumbnail')->getClientOriginalExtension();
        //     Image::make(Request::file('image_thumbnail'))
        //         ->resize(500, 500)
        //         ->save(public_path() . $destinationPath . $imageName)
        //     ;
        //     $game->image_thumbnail_path = $destinationPath . $imageName;
        //     if (!$game->save()) {
        //        Session::flash('alert-danger', 'Could not save Game thumbnail!');
        //         return Redirect::back();
        //     }
        // }

        // if (Request::file('image_header')) {
        //     Storage::delete($game->image_header_path);
        //     $imageName  = 'header.' . Request::file('image_header')->getClientOriginalExtension();
        //     Image::make(Request::file('image_header'))
        //         ->resize(1600, 400)
        //         ->save(public_path() . $destinationPath . $imageName)
        //     ;
        //     $game->image_header_path = $destinationPath . $imageName;
        //     if (!$game->save()) {
        //         Session::flash('alert-danger', 'Could not save Game Header!');
        //         return Redirect::back();
        //     }
        // }
        // Session::flash('alert-success', 'Successfully saved Game!');
        // return Redirect::to('admin/games/' . $game->slug);

        Session::flash('alert-danger', 'Could not save GameServer!');
        return Redirect::back();
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
        if ($game->gamecommandhandler == 0 || $game->gamecommandhandler == 1) 
        {
            $Query = new SourceQuery( );
            try
            {   
                if ($game->gamecommandhandler == 0)
                {
                    $Query->Connect( $gameServer->address, $gameServer->rcon_port, 1, SourceQuery::GOLDSOURCE );
                }
                if ($game->gamecommandhandler == 1)
                {
                    $Query->Connect( $gameServer->address, $gameServer->rcon_port, 1, SourceQuery::SOURCE );
                }
                $Query->SetRconPassword( $gameServer->rcon_password );
                $result = $Query->Rcon( $command );
            }
            catch( Exception $e )
            {
                $error = $e->getMessage();
            }
            finally
            {
                $Query->Disconnect( );
            }

            if (!isset($error) || $result != false)
            {
                Session::flash('alert-success', 'Successfully executed command "' . $command .'" with connector ' . Helpers::getGameCommandHandler()[$game->gamecommandhandler] . ' Result:' . var_export($result, true));
            }
            else 
            {
                Session::flash('alert-danger', 'error while executing command "' . $command .'" with connector ' . Helpers::getGameCommandHandler()[$game->gamecommandhandler] .' Error:' . var_export($error, true) . ' Result:'. var_export($result, true));
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
     * @param  EventTournamentServer $match
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
