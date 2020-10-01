<?php

namespace App;

use Storage;

use Illuminate\Database\Eloquent\Model;

use GuzzleHttp\Client;
use Lanops\Challonge\Challonge;

use Cviebrock\EloquentSluggable\Sluggable;

class Game extends Model
{
    use Sluggable;

    /**
     * The name of the table.
     *
     * @var string
     */
    protected $table = 'games';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'version',
        'name',
        'active',
        'gamecommandhandler',
        'image_header_path',
        'image_thumbnail_path'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = array(
        'created_at',
        'updated_at'
    );
   
    public static function boot()
    {
        parent::boot();
        self::deleted(function ($model) {
            if ($model->image_thumbnail_path || $model->image_header_path) {
                // TODO - redo storage and image calls like this
                Storage::deleteDirectory('public/images/games/' . $model->slug . '/');
            }
        });
    }

    /*
     * Relationships
     */
    public function eventTournaments()
    {
        return $this->hasMany('App\EventTournament');
    }
    public function gameServers()
    {
        return $this->hasMany('App\GameServer');
    }
    public function gameServerCommands()
    {
        return $this->hasMany('App\GameServerCommand');
    }

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }
    
    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function getGameServerCommandSelectArray(){
        $return = array();
        foreach(GameServerCommand::where(['game_id' => $this->id, 'scope' => 0])->get() as $gameServerCommand){
            $return[$gameServerCommand->id] = $gameServerCommand->name; 
        }

        return $return;
    }

    public static function getGameSelectArray($publicOnly = true)
    {
        $return[0] = 'None';
        foreach (Game::where('public', $publicOnly)->orderBy('name', 'ASC')->get() as $game) {
            $return[$game->id] = $game->name;
        }
        return $return;
    }
}
