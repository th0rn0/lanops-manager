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

        // GoLDSOURCE OR SOURCE
        if ($game->gamecommandhandler == 0 || $game->gamecommandhandler == 1) {
            $Query = new SourceQuery();
            try {
                if ($game->gamecommandhandler == 0) {
                    $Query->Connect($gameServer->address, $gameServer->rcon_port, 1, SourceQuery::GOLDSOURCE);
                }
                if ($game->gamecommandhandler == 1) {
                    $Query->Connect($gameServer->address, $gameServer->rcon_port, 1, SourceQuery::SOURCE);
                }
                // $Query->SetRconPassword($gameServer->rcon_password);
                // $result = $Query->Rcon($command);
                $result->info = $Query->GetInfo();
                $result->players = $Query->GetPlayers();
            } catch (Exception $e) {
                $result->error = $e->getMessage();
            } finally {
                $Query->Disconnect();
            }
        } else {
            // ManiaPlanet dedicated server SDK
            if ($game->gamecommandhandler == 2) {
                try {
                    $maniaConnection = new Connection($gameServer->address, $gameServer->rcon_port, 5, "SuperAdmin", $gameServer->rcon_password, Connection::API_2011_02_21);

                    // Argument 1 => Trackmania * Forever
                    $challengeInfo = json_decode(json_encode($maniaConnection->execute("GetCurrentChallengeInfo", array(1))));
                    $players = $maniaConnection->getPlayerList();

                    $result->info = new \stdClass();
                    $result->info->Map =  @$challengeInfo->Name;
                    $result->info->Players =  @count($players);
                } catch (Exception $e) {
                    $result->error = $e->getMessage();
                }
            }
        }

        return json_encode($result);
    }
}
