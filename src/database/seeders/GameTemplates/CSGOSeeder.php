<?php

namespace Database\Seeders\GameTemplates;

use Illuminate\Database\Seeder;
use App\SliderImage;
use Faker\Factory as Faker;
use App\Game;
use App\GameServerCommand;
use App\GameServerCommandParameter;
use App\GameServer;

class CSGOSeeder extends Seeder
{

    public string $name = 'Counter-Strike: Global Offensive';
    public string $description = 'Best game ever';
    public string $version = "latest";
    public bool $public = false;
    public int $gamecommandhandler = 2;
    public string $connect_game_url = 'steam://connect/{>gameServer->address}:{>gameServer->game_port}/{>§gameServer->game_password}';
    public string $connect_game_command = 'password {>§gameServer->game_password}; connect {>gameServer->address}:{>gameServer->game_port}';
    public string $connect_stream_url = 'steam://connect/{>gameServer->address}:{>gameServer->stream_port}';
    public int $min_team_count = 2;
    public int $max_team_count = 2;
    public int $gamematchapihandler = 1;
    public bool $matchmaking_enabled = true;
    public bool $matchmaking_autostart = true;
    public bool $matchmaking_autoapi = true;

    public string $matchstart_name = 'Load Match Get5';
    public string $matchstart_command = 'get5_loadmatch_url "{>gamematchapiurl->matchconfigapi}" Authorization "Bearer {>gameServer->gameserver_secret}"';

    public array $game_command_parameters = [
        [
            'name' => 'mapCasual',
            'options' => 'de_dust2;de_mirage;de_inferno;de_overpass;de_vertigo;de_train'
        ],
    ];

    public array $additional_match_commands = [
        [
            'name' => 'get5 match status',
            'command' => 'get5_status'
        ],
        [
            'name' => 'get5 end match',
            'command' => 'get5_endmatch'
        ],
        [
            'name' => 'get5 force ready',
            'command' => 'get5_forceready'
        ]
    ];

    public array $additional_gameserver_commands = [
        [
            'name' => 'unban all',
            'command' => 'removeallids'
        ],
        [
            'name' => 'skip warmup',
            'command' => 'mp_warmup 0'
        ],
        [
            'name' => 'end warmup',
            'command' => 'mp_warmup_end'
        ],
        [
            'name' => 'changelevel casual',
            'command' => 'changelevel {>mapcasual}'
        ]
    ];


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

        if (!$game) {
            throw new \Exception("Game $this->name could not be created! get Support!");
        }

        foreach ($this->game_command_parameters as $parameter) {
            $createdparameter = $game->gameServerCommandParameters()->create(
                [
                    'name' => $parameter['name'],
                    'options' => $parameter['options']
                ]
            );
            if (!$createdparameter) {
                throw new \Exception("GameServerCommandParameters " . $parameter['name'] . " could not be created! get Support!");
            }
        }


        foreach ($this->additional_match_commands as $additional_match_command) {
            $createdcommand = $game->gameServerCommands()->create(
                [
                    'name' => $additional_match_command['name'],
                    'command' => $additional_match_command['command'],
                    'scope' => 1
                ]
            );
            if (!$createdcommand) {
                throw new \Exception("GameServerCommand " . $additional_match_command['name'] . " could not be created! get Support!");
            }
        }

        foreach ($this->additional_gameserver_commands as $additional_gameserver_command) {
            $createdcommand = $game->gameServerCommands()->create(
                [
                    'name' => $additional_gameserver_command['name'],
                    'command' => $additional_gameserver_command['command'],
                    'scope' => 0
                ]
            );
            if (!$createdcommand) {
                throw new \Exception("GameServerCommand " . $additional_gameserver_command['name'] . " could not be created! get Support!");
            }
        }



        $command = $game->gameServerCommands()->create(
            [
                'name' => $this->matchstart_name,
                'command' => $this->matchstart_command,
                'scope' => 1
            ]
        );

        if (!$command) {
            throw new \Exception("Matchstart GameServerCommand $this->matchstart_name could not be created! get Support!");
        }

        $game->matchStartgameServerCommand = $command->id;





        if (!$game->save()) {
            throw new \Exception("Game $this->name could not be saved after assigning the Match start command! get Support!");
        }
    }
}
