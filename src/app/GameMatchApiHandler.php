<?php

namespace App;

use Exception;
use Illuminate\Http\Request;
use App\MatchMaking;
use App\EventTournament;


class GameMatchApiHandler
{
    public static function getGameMatchApiHandlerSelectArray()
    {
        $return = array(
            "0" => "None",
            "1" => "Get5",
        );
        return $return;
    }

    public function getGameMatchApiHandler($matchApiHandlerId) : IGameMatchApiHandler
    {
        switch ($matchApiHandlerId)
        {
            case "1":
                return new Get5MatchApiHandler();
            default:
                throw new Exception("MatchApiHandler \"" . GameMatchApiHandler::getGameMatchApiHandlerSelectArray()[$matchApiHandlerId] . "\" is not able to execute commands.");
        }
    }
}

 /**
    * IGameMatchApiHandler
    * @param $matchtype (0 for tournament, 1 for matchmaking)
    * @return View
    */
interface IGameMatchApiHandler
{
    public function getconfig($matchid, $nummaps, $players_per_team, $apiurl, $apikey);
    public function getuserthirdpartyrequirements();
    public function addteam($name);
    public function addplayer ($teamName, $thirdpartyid, $thirdpartyname, $userid, $username);
    public function authorizeserver(Request $request, $serverkey);
    public function golive(Request $request, MatchMaking $match = null, EventTournament $tournament = null, ?int $challongematchid, int $mapnumber);
    public function updateround(Request $request, MatchMaking $match = null, EventTournament $tournament = null, ?int $challongematchid, int $mapnumber);
    public function updateplayer(Request $request, MatchMaking $match = null, EventTournament $tournament = null, ?int $challongematchid, int $mapnumber, string $player);
    public function finalizemap(Request $request, MatchMaking $match = null, EventTournament $tournament = null, ?int $challongematchid, int $mapnumber);
    public function finalize(Request $request, MatchMaking $match = null, EventTournament $tournament = null, ?int $challongematchid);
}

class Get5MatchApiHandler implements IGameMatchApiHandler
{
    private $result;

    public function __construct()
    {
        $this->result = new \stdClass();
        $this->result->min_spectators_to_ready = 0;
        $this->result->skip_veto = false;
        $this->result->veto_first = "team1";
        $this->result->side_type = "standard";
        $this->result->maplist = array(
            "de_vertigo",
            "de_dust2",
            "de_inferno",
            "de_mirage",
            "de_nuke",
            "de_overpass",
            "de_ancient"
        );
    }

    public function getuserthirdpartyrequirements()
    {
        $return = array(
            "thirdpartyid" => "steamid",
            "thirdpartyname" => "steamname",
        );
        return $return;
    }

    public function addteam($name)
    {
        if (!isset($this->result->team1))
        {
            $this->result->team1 = new \stdClass();
            $this->result->team1->name = $name;
            $this->result->team1->tag = $name;
            $this->result->team1->flag = "DE";
            
        }
        elseif (!isset($this->result->team2))
        {
            $this->result->team2 = new \stdClass();
            $this->result->team2->name = $name;
            $this->result->team2->tag = $name;
            $this->result->team2->flag = "DE";
        }
        else
        {
            throw new Exception("MatchApiHandler for get5 does not support more than 2 Teams!");
        }
    }

    public function addplayer ($teamName, $thirdpartyid, $thirdpartyname, $userid, $username)
    {
        $team = null;

        if ($teamName == $this->result->team1->name)
        {
            $team = $this->result->team1;
        }
        elseif ($teamName == $this->result->team2->name)
        {
            $team = $this->result->team2;
        }

        if (!isset($team->players))
        {
            $team->players = new \stdClass();
        }

        $team->players->{$thirdpartyid} = $thirdpartyname;
    }

    public function getconfig($matchid, $nummaps, $players_per_team, $apiurl, $apikey)
    {
        $this->result->matchid = "Match $matchid";
        $this->result->num_maps = intval ($nummaps);
        $this->result->players_per_team = $players_per_team;
        $this->result->min_players_to_ready = $players_per_team;
        if ($apikey != null && $apiurl != null)
        {
            $this->result->cvars = new \stdClass();
            $this->result->cvars->get5_eventula_apistats_key = $apikey;
            $this->result->cvars->get5_eventula_apistats_url = $apiurl;
        }

 
        return $this->result;

    }


    public function authorizeserver(Request $request, $serverkey)
    {
        if ($serverkey != $request->key)
        {
            return false;
        }
        else
        {
            return true;
        }
    }




    public function golive(Request $request, MatchMaking $match = null, EventTournament $tournament = null, ?int $challongematchid, int $mapnumber)
    {
        if ($match != null && $tournament == null) {
            if (!$match->setStatus('LIVE')) {
                return false;
            }
            return true;
        }
        if($match == null && $tournament != null && $challongematchid != null)
        {
            //tournament stuff
            return true;
        }
        return false;
    }

    public function updateround(Request $request, MatchMaking $match = null, EventTournament $tournament = null, ?int $challongematchid, int $mapnumber)
    {

        if($match != null && $tournament == null)
        {
            $loop = 1;
            foreach ($match->teams as $team) {
                $team->team_score = $request->{"team".$loop."score"};
                if (!$team->save()) {
                   return false;
                }
                $loop++;
            }
            return true;
        }
        if($match == null && $tournament != null && $challongematchid != null)
        {
            $tournament->updateMatchScores($challongematchid, $request->{"team1score"}, $request->{"team2score"});
            return true;
        }
        return false;



    }
    public function updateplayer(Request $request, MatchMaking $match = null, EventTournament $tournament = null, ?int $challongematchid, int $mapnumber, string $player)
    {
        if($match != null && $tournament == null)
        {
            //matchmaking stuff
            return true;
        }
        if($match == null && $tournament != null && $challongematchid != null)
        {
            //tournament stuff
            return true;
        }
        return false;

    }

    public function finalizemap(Request $request, MatchMaking $match = null, EventTournament $tournament = null, ?int $challongematchid, int $mapnumber)
    {
        if($match != null && $tournament == null)
        {
            if (!$this->updateround($request,$match,null,null,$mapnumber))
            {
                return false;
            }
            return true;

        }
        if($match == null && $tournament != null && $challongematchid != null)
        {
            if (($mapnumber + 1) == $tournament->getnummaps($challongematchid))
            {
                $tournament->updateMatch($challongematchid, $request->{"team1score"}, $request->{"team2score"});
                return true;
            }
            if (!$this->updateround($request,null,$tournament,$challongematchid,$mapnumber))
            {
                return false;
            }
            return true;
        }
        return false;
    }    
    public function finalize(Request $request, MatchMaking $match = null, EventTournament $tournament = null, ?int $challongematchid)
    {
        if($match != null && $tournament == null)
        {
            if (!$match->setStatus('COMPLETE'))
            {
                return false;
            }
            return true;

        }
        if($match == null && $tournament != null && $challongematchid != null)
        {
            //tournament stuff
            
        }
        return false;
        
    }

}