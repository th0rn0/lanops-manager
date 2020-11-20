<?php

namespace App\Http\Controllers\Api\GameMatchApi;

use DB;
use Auth;

use App\Event;
use App\EventTournament;
use App\EventTimetable;
use App\EventTimetableData;
use App\EventParticipant;
use App\EventParticipantType;
use App\GameMatchApiHandler;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class GameMatchApiController extends Controller
{
    /**
     * Show Events
     * @return View
     */
    public function tournamentMatchConfig(Event $event, EventTournament $tournament, int $challongeMatchId, $nummaps)
    {
        $match = $tournament->getMatch($challongeMatchId);
        if (!$match) {
            return "No Match found for $challongeMatchId";
        }

        $gamematchapihandler = (new GameMatchApiHandler())->getGameMatchApiHandler($tournament->game->gamematchapihandler);
        
        $team1 = $tournament->getTeamByChallongeId($match->player1_id);
        $team2 = $tournament->getTeamByChallongeId($match->player2_id);

        $gamematchapihandler->addteam($team1->name);
        $gamematchapihandler->addteam($team2->name);

        foreach ($team1->tournamentParticipants as $key => $team1Participant) {
            $eventParticipant = $team1Participant->eventParticipant;
            $user = $eventParticipant->user;
            $gamematchapihandler->addplayer($team1->name, $user->steamid, $user->steamname);
        }
        foreach ($team2->tournamentParticipants as $key => $team2Participant) {
            $eventParticipant = $team2Participant->eventParticipant;
            $user = $eventParticipant->user;
            $gamematchapihandler->addplayer($team2->name, $user->steamid, $user->steamname);
        }

        $result = $gamematchapihandler->start($challongeMatchId,$nummaps, $tournament->team_size[0]);

        return response()->json($result)->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }

}
