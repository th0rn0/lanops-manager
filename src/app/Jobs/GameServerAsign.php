<?php

namespace App\Jobs;

use App\EventTournament;
use App\EventTournamentMatchServer;
use App\MatchMakingServer;
use App\MatchMaking;
use App\Http\Controllers\Admin\GameServerCommandsController;
use Illuminate\Http\Request;
use Debugbar;


use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GameServerAsign implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 15;

    /**
     * The Matchmaking instance.
     *
     * @var int;
     */
    protected $type;

    /**
     * The Matchmaking instance.
     *
     * @var \App\MatchMaking
     */
    protected $matchmakingmatch;

    /**
     * The tournament instance.
     *
     * @var \App\EventTournament
     */
    protected $EventTournament;

    /**
     * The challonge match id.
     *
     * @var int
     */
    protected $challongematchid;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(?MatchMaking $matchmakingmatch, ?Eventtournament $eventTournament, ?int $challongematchid)
    {

        if (isset($matchmakingmatch)) {
            $this->matchmakingmatch = $matchmakingmatch;
            $this->type = 1;
        }
        if (isset($eventTournament) && isset($challongematchid)) {
            $this->EventTournament = $eventTournament;
            $this->challongematchid = $challongematchid;
            $this->type = 2;
        }
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->type == 1) {


            $matchserver = MatchMakingServer::getMatchMakingServer($this->matchmakingmatch->id);
            if (!isset($matchserver)) {
                $availableservers = $this->matchmakingmatch->game->getGameServerSelectArray();

                if (!isset($availableservers) || count($availableservers) == 0) {
                    $this->release(30);
                    return false;
                }

                $matchMakingServer                 = new MatchMakingServer();
                $matchMakingServer->match_id        = $this->matchmakingmatch->id;
                $matchMakingServer->game_server_id = array_key_first($availableservers);

                if (!$matchMakingServer->save()) {
                    $this->release(30);
                    return false;
                }


                if (isset($this->matchmakingmatch->game->matchStartGameServerCommand) &&  $this->matchmakingmatch->game->matchStartGameServerCommand != null) {
                    $request = new Request([
                        'command'   => $this->matchmakingmatch->game->matchStartGameServerCommand->id,
                    ]);

                    $gccontroller = new GameServerCommandsController();
                    if (!$gccontroller->internalExecuteGameServerMatchMakingCommand($this->matchmakingmatch->game, $matchMakingServer->gameServer, $this->matchmakingmatch, $request)) {
                        $this->release(30);
                        return false;
                    }
                }
                return true;
            }
            else
            {
                if (isset($this->matchmakingmatch->game->matchStartGameServerCommand) &&  $this->matchmakingmatch->game->matchStartGameServerCommand != null) {
                    $request = new Request([
                        'command'   => $this->matchmakingmatch->game->matchStartGameServerCommand->id,
                    ]);

                    $gccontroller = new GameServerCommandsController();
                    if (!$gccontroller->internalExecuteGameServerMatchMakingCommand($this->matchmakingmatch->game, $matchserver->gameServer, $this->matchmakingmatch, $request)) {
                        $this->release(30);
                        return false;
                    }
                }
                return true;
            }
        }

        if ($this->type == 2) {
            $matchserver = EventTournamentMatchServer::getTournamentMatchServer($this->challongematchid);
            if (!isset($matchserver)) {
                $availableservers = $this->EventTournament->game->getGameServerSelectArray();
                //möglicherweise weiter oben prüfen!
                if (!isset($availableservers) || count($availableservers) == 0) {
                    $this->release(30);
                    return false;
                }

                $tournamentMatchServer                 = new EventTournamentMatchServer();
                $tournamentMatchServer->challonge_match_id        = $this->challongematchid;
                $tournamentMatchServer->game_server_id = array_key_first($availableservers);

                if (!$tournamentMatchServer->save()) {
                    $this->release(30);
                    return false;
                }

                if (isset($this->EventTournament->game->matchStartGameServerCommand) &&  $this->EventTournament->game->matchStartGameServerCommand != null) {
                    $request = new Request([
                        'command'   => $this->EventTournament->game->matchStartGameServerCommand->id,
                        'challonge_match_id'   => $this->challongematchid,
                    ]);

                    $gccontroller = new GameServerCommandsController();
                    if (!$gccontroller->internalExecuteGameServerTournamentMatchCommand($this->EventTournament->game, $tournamentMatchServer->gameServer, $this->EventTournament, $request)) {
                        $this->release(30);
                        return false;
                    }
                }
                return true;
            } else {
                if (isset($this->EventTournament->game->matchStartGameServerCommand) &&  $this->EventTournament->game->matchStartGameServerCommand != null) {
                    $request = new Request([
                        'command'   => $this->EventTournament->game->matchStartGameServerCommand->id,
                        'challonge_match_id'   => $this->challongematchid,
                    ]);

                    $gccontroller = new GameServerCommandsController();
                    if (!$gccontroller->internalExecuteGameServerTournamentMatchCommand($this->EventTournament->game, $matchserver->gameServer, $this->EventTournament, $request)) {
                        $this->release(30);
                        return false;
                    }
                }
                return true;
            }
        }
    }
}
