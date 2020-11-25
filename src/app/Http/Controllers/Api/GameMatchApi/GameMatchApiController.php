<?php

namespace App\Http\Controllers\Api\GameMatchApi;

use DB;
use Auth;
use Illuminate\Support\Facades\Storage;

use App\Event;
use App\EventTournament;
use App\EventTimetable;
use App\EventTimetableData;
use App\EventParticipant;
use App\EventParticipantType;
use App\EventTournamentMatchServer;
use App\GameMatchApiHandler;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\MatchMaking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class GameMatchApiController extends Controller
{
    /**
     * tournamentMatchConfig
     * @return View
     */
    public function tournamentMatchConfig(Event $event, EventTournament $tournament, int $challongeMatchId, int $nummaps)
    {
        $match = $tournament->getMatch($challongeMatchId);
        if (!$match) {
            return "No Match found for $challongeMatchId";
        }
        if (isset($tournament->game) && isset($tournament->game->gamematchapihandler)) 
        {
            $gamematchapihandler = (new GameMatchApiHandler())->getGameMatchApiHandler($tournament->game->gamematchapihandler);
            
            $team1 = $tournament->getTeamByChallongeId($match->player1_id);
            $team2 = $tournament->getTeamByChallongeId($match->player2_id);

            $gamematchapihandler->addteam($team1->name);
            $gamematchapihandler->addteam($team2->name);

            foreach ($team1->tournamentParticipants as $key => $team1Participant) {
                $eventParticipant = $team1Participant->eventParticipant;
                $user = $eventParticipant->user;
                $gamematchapihandler->addplayer($team1->name, $user->steamid, $user->steamname, $user->id, $user->username);
            }
            foreach ($team2->tournamentParticipants as $key => $team2Participant) {
                $eventParticipant = $team2Participant->eventParticipant;
                $user = $eventParticipant->user;
                $gamematchapihandler->addplayer($team2->name, $user->steamid, $user->steamname, $user->id, $user->username);
            }

            $matchserver = EventTournamentMatchServer::getTournamentMatchServer($challongeMatchId);
            //replace matchmaking_autofinalize
            if (isset($matchserver->gameServer->gameserver_secret) && $tournament->game->matchmaking_autofinalize)
            {
                $apiurl = config('app.url')."/api/events/".$tournament->event->slug."/tournaments/".$tournament->slug."/".$challongeMatchId."/";
                $result = $gamematchapihandler->getconfig($challongeMatchId, $nummaps, $tournament->team_size[0],$apiurl, $matchserver->gameServer->gameserver_secret);
            }
            else
            {
                $result = $gamematchapihandler->getconfig($challongeMatchId, $nummaps, $tournament->team_size[0], null, null);
            }

        }
        else
        {
            return "no gamematchapihandler for match available!";
        }


        return response()->json($result)->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    } 
    
    /**
    * matchMakingMatchConfig
    * @return View
    */
   public function matchMakingMatchConfig(MatchMaking $match, int $nummaps)
   {
       if(isset($match->game) && isset ($match->game->gamematchapihandler))
       {
            $gamematchapihandler = (new GameMatchApiHandler())->getGameMatchApiHandler($match->game->gamematchapihandler);
        
        
        foreach( $match->teams as $team)
        {
            $gamematchapihandler->addteam($team->name);

            foreach ($team->players as $player) {
                if (isset($player->user->steamid) && isset($player->user->steamname)) 
                {
                    $gamematchapihandler->addplayer($team->name, $player->user->steamid, $player->user->steamname, $player->user->id, $player->user->username);
                }
            }

        }
            if (isset($match->matchMakingServer->gameServer->gameserver_secret) && $match->game->matchmaking_autofinalize)
            {
                $apiurl = config('app.url')."/api/matchmaking/".$match->id."/";
                $result = $gamematchapihandler->getconfig($match->id,$nummaps, $match->team_size, $apiurl, $match->matchMakingServer->gameServer->gameserver_secret);
            }
            else
            {
                $result = $gamematchapihandler->getconfig($match->id,$nummaps, $match->team_size, null, null);
            }
       }
       else
       {
           return "no gamematchapihandler for match available!";
       }
       

       return response()->json($result)->setEncodingOptions(JSON_UNESCAPED_UNICODE);
   }

    /**
    * matchMakingMatchGolive
    * @param Request $request
    * @param MatchMaking $match
    * @param int $mapnumber
    * @return View
    */
    public function matchMakingMatchGolive(Request $request, MatchMaking $match, int $mapnumber)
    {
        if(!isset($match->matchMakingServer->gameServer))
        {
            return "Error: No GameServer setuped for this match!";
        }
        if (!isset($match->game->gamematchapihandler)) 
        {
            return "Error: No gamematchapihandler setuped for this match!";
        }
        else
        {
            $gamematchapihandler = (new GameMatchApiHandler())->getGameMatchApiHandler($match->game->gamematchapihandler);
        }

        if(!$gamematchapihandler->authorizeserver($request, $match->matchMakingServer->gameServer->gameserver_secret))
        {
            return "Error: Gameserver Secret Key is wrong!";
        }

        if ($mapnumber != 1)
        {
            return "Error: In matchmaking only one map is possible!";
        }

        if ($match->status != "WAITFORPLAYERS")
        {
            return "Error: Status is not WAITFORPLAYERS!";
        }

        if(!$gamematchapihandler->golive($request, $match , null , $mapnumber))
        {
            return "Error: GoLive failed!";
        }

        return 'Match Started successfully!';

    }

    /**
    * matchMakingMatchFinalize
    * @param Request $request
    * @param MatchMaking $match
    * @return View
    */
    public function matchMakingMatchFinalize(Request $request, MatchMaking $match)
    {
        if(!isset($match->matchMakingServer->gameServer))
        {
            return "Error: No GameServer setuped for this match!";
        }
        if (!isset($match->game->gamematchapihandler)) 
        {
            return "Error: No gamematchapihandler setuped for this match!";
        }
        else
        {
            $gamematchapihandler = (new GameMatchApiHandler())->getGameMatchApiHandler($match->game->gamematchapihandler);
        }

        if(!$gamematchapihandler->authorizeserver($request, $match->matchMakingServer->gameServer->gameserver_secret))
        {
            return "Error: Gameserver Secret Key is wrong!";
        }

        if (!$gamematchapihandler->finalize($request, $match, null))
        {
            return "Error: Finalizing failed!";
        }
        else
        {
            return "Success: Finalized Match!";
        }


    }

    /**
    * matchMakingMatchFinalizeMap
    * @param Request $request
    * @param MatchMaking $match
    * @param int $mapnumber
    * @return View
    */
    public function matchMakingMatchFinalizeMap(Request $request, MatchMaking $match, int $mapnumber)
    {
        if(!isset($match->matchMakingServer->gameServer))
        {
            return "Error: No GameServer setuped for this match!";
        }
        if(!isset($match->matchMakingServer->gameServer))
        {
            return "Error: No GameServer setuped for this match!";
        }
        if (!isset($match->game->gamematchapihandler)) 
        {
            return "Error: No gamematchapihandler setuped for this match!";
        }
        else
        {
            $gamematchapihandler = (new GameMatchApiHandler())->getGameMatchApiHandler($match->game->gamematchapihandler);
        }

        if(!$gamematchapihandler->authorizeserver($request, $match->matchMakingServer->gameServer->gameserver_secret))
        {
            return "Error: Gameserver Secret Key is wrong!";
        }      
        if(!$gamematchapihandler->finalizemap($request, $match, null, $mapnumber))
        {
            return "Error: finalizemap failed!";
        }

        return 'Map finalized successfully!';


    }

    /**
    * matchMakingMatchUpdateround
    * @param Request $request
    * @param MatchMaking $match
    * @param int $mapnumber
    * @return View
    */
    public function matchMakingMatchUpdateround(Request $request, MatchMaking $match, int $mapnumber)
    {
        if(!isset($match->matchMakingServer->gameServer))
        {
            return "Error: No GameServer setuped for this match!";
        }
        if (!isset($match->game->gamematchapihandler)) 
        {
            return "Error: No gamematchapihandler setuped for this match!";
        }
        else
        {
            $gamematchapihandler = (new GameMatchApiHandler())->getGameMatchApiHandler($match->game->gamematchapihandler);
        }

        if(!$gamematchapihandler->authorizeserver($request, $match->matchMakingServer->gameServer->gameserver_secret))
        {
            return "Error: Gameserver Secret Key is wrong!";
        }

        if(!$gamematchapihandler->updateround($request, $match, null, $mapnumber))
        {
            return "Error: updateround failed!";
        }
        return 'round updated successfully!';

    }

    /**
    * matchMakingMatchUpdateplayer
    * @param Request $request
    * @param MatchMaking $match
    * @param int $mapnumber
    * @return View
    */
    public function matchMakingMatchUpdateplayer(Request $request, MatchMaking $match, int $mapnumber, string $player)
    {
        if(!isset($match->matchMakingServer->gameServer))
        {
            return "Error: No GameServer setuped for this match!";
        }
        if (!isset($match->game->gamematchapihandler)) 
        {
            return "Error: No gamematchapihandler setuped for this match!";
        }
        else
        {
            $gamematchapihandler = (new GameMatchApiHandler())->getGameMatchApiHandler($match->game->gamematchapihandler);
        }

        if(!$gamematchapihandler->authorizeserver($request, $match->matchMakingServer->gameServer->gameserver_secret))
        {
            return "Error: Gameserver Secret Key is wrong!";
        }

        if(!$gamematchapihandler->updateplayer($request, $match, null, $mapnumber))
        {
            return "Error: updateplayer failed!";
        }
        return 'player updated successfully!';


    }





}
