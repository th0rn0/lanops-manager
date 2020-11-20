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
    public function start($address, $rconPort, $password);
    public function finalize($command);
}

class Get5MatchApiHandler implements IGameMatchApiHandler
{

    public function start($address, $rconPort, $password)
    {
      
    }

    public function finalize($command)
    {
       
    }

}