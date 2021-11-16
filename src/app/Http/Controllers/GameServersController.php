<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use Session;
use Storage;
use Image;
use File;

use App\Game;
use App\GameServer;
use App\GameCommandHandler;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

use xPaw\SourceQuery\SourceQuery;
use Maniaplanet\DedicatedServer\Connection;

class GameServersController extends Controller
{


    /**
     * Get status for casual gameservers 
     * @param  Game  $game
     * @param  GameServer  $gameServer
     * @return $gameServerStatus
     */
    public function status(Game $game, GameServer $gameServer)
    {
        $result = new \stdClass();
        
        $gameCommandhandler = (new GameCommandHandler())->getGameCommandHandler($game->gamecommandhandler);
        $gameCommandhandler->init($gameServer->rcon_address ?? $gameServer->address, $gameServer->rcon_port, $gameServer->rcon_password);
        $result=$gameCommandhandler->status();
        $gameCommandhandler->dispose();

        return json_encode($result);
    }
}
