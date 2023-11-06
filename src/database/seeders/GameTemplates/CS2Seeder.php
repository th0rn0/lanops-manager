<?php

namespace Database\Seeders\GameTemplates;

use Illuminate\Database\Seeder;
use App\SliderImage;
use Faker\Factory as Faker;
use App\Game;
use App\GameServerCommand;
use App\GameServerCommandParameter;
use App\GameServer;

class CS2Seeder extends Seeder
{

    public string $name = 'CounterStrike 2';
    public string $description = 'Best game ever';
    public string $version = "latest";
    public bool $public = true;
    public int $gamecommandhandler = 2;
    public string $connect_game_url = 'steam://connect/{>gameServer->address}:{>gameServer->game_port}/{>§gameServer->game_password}';
    public string $connect_game_command = 'password {>§gameServer->game_password}; connect {>gameServer->address}:{>gameServer->game_port}';
    public string $connect_stream_url = 'steam://connect/{>gameServer->address}:{>gameServer->stream_port}';
    public int $min_team_count = 2;
    public int $max_team_count = 2;
    public int $gamematchapihandler = 2;
    public bool $matchmaking_enabled = true;
    public bool $matchmaking_autostart = true;
    public bool $matchmaking_autoapi = true;

    public string $matchstart_name = 'Load Match Pugsharp';
    public string $matchstart_command = 'ps_loadconfig "{>gamematchapiurl->matchconfigapi}" "Bearer {>gameServer->gameserver_secret}"';



    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        $game = Game::firstOrCreate(
            [
                'name' => $this->name
            ],
            [
                'description'               => $this->description,
                'version'                   => $this->version,
                'public'                    => $this->public,
                'gamecommandhandler'        => $this->gamecommandhandler,
                'connect_game_url'          => $this->connect_game_url,
                'connect_game_command'          => $this->connect_game_command,
                'connect_stream_url'          => $this->connect_stream_url,
                'min_team_count'          => $this->min_team_count,
                'max_team_count'          => $this->max_team_count,
                'gamematchapihandler'          => $this->gamematchapihandler,
                'matchmaking_enabled'          => $this->matchmaking_enabled,
                'matchmaking_autostart'          => $this->matchmaking_autostart,
                'matchmaking_autoapi'          => $this->matchmaking_autoapi
            ]
        );


        $command = $game->gameServerCommands()->create(
            [
                'name' => $this->matchstart_name,
                'command' => $this->matchstart_command,
                'scope' => 1
            ]
        );

        $game->matchStartgameServerCommand = $command->id;


        if (!$game->save()) {
            throw new \Exception("Game $this->name could not be saved after assigning the Match start command! get Support!");
        }


    }
}


