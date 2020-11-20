<?php

namespace App;

use Exception;

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

interface IGameMatchApiHandler
{
    public function start($matchid, $nummaps, $players_per_team);
    public function finalize($command);
    public function addteam($name);
    public function addplayer ($teamName, $id, $name);
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
            "de_cache",
            "de_dust2",
            "de_inferno",
            "de_mirage",
            "de_nuke",
            "de_overpass",
            "de_train"
        );
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

    public function addplayer ($teamName, $id, $name)
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

        $team->players->{$id} = $name;
    }

    public function start($matchid, $nummaps, $players_per_team)
    {
        $this->result->matchid = "Match $matchid";
        $this->result->num_maps = intval ($nummaps);
        $this->result->players_per_team = $players_per_team;
        $this->result->min_players_to_ready = $players_per_team;
 
        return $this->result;

    }

    public function finalize($command)
    {
       
    }

}