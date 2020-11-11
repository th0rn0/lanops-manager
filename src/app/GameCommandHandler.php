<?php

namespace App;

use xPaw\SourceQuery\SourceQuery;
use Maniaplanet\DedicatedServer\Connection;

use Exception;

class GameCommandHandler
{
    public static function getGameCommandHandlerSelectArray()
    {
        $return = array(
            "0" => "None",
            "1" => "SourceQuery GoldSource",
            "2" => "SourceQuery Source",
            "3" => "Maniaplanet XRPC",
        );
        return $return;
    }

    public function getGameCommandHandler($commandHandlerId) : IGameCommandHandler
    {
        switch ($commandHandlerId)
        {
            case "1":
                return new SourceQueryCommandHandler(SourceQuery::GOLDSOURCE);
            case "2":
                return new SourceQueryCommandHandler(SourceQuery::SOURCE);
            case "3":
                return new ManiaplanetXrpcCommandHandler();
            default:
                throw new Exception("CommandHandler \"" . GameCommandHandler::getGameCommandHandlerSelectArray()[$commandHandlerId] . "\" is not able to execute commands.");
        }
    }
}

interface IGameCommandHandler
{
    public function init($address, $rconPort, $password);
    public function execute($command);
    public function dispose();
}

class SourceQueryCommandHandler implements IGameCommandHandler
{
    private $query, $sourceQueryType;

    public function __construct($sourceQueryType)
    {
        $this->query = new SourceQuery();
        $this->sourceQueryType = $sourceQueryType;
    }

    public function init($address, $rconPort, $password)
    {
        $this->query->Connect($address, $rconPort, 1, SourceQuery::GOLDSOURCE);
        $this->query->SetRconPassword($password);
    }

    public function execute($command)
    {
        // return $this->query->GetInfo();
        $result = $this->query->Rcon($command);
        if($result == false)
        {
            throw new Exception("No Connection possible");
        }

        return $result;
    }

    public function dispose()
    {
        $this->query->Disconnect();
    }
}

class ManiaplanetXrpcCommandHandler implements IGameCommandHandler
{
    private $maniaConnection;

    public function init($address, $rconPort, $password)
    {
        $this->maniaConnection = new Connection($address, $rconPort, 5, "SuperAdmin", $password, Connection::API_2011_02_21);
    }

    public function execute($command)
    {
        return $this->maniaConnection->execute($command);
    }

    public function dispose()
    {
        # nothing to do
    }
}