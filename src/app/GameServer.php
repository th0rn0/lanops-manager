<?php

namespace App;

use Storage;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Authenticatable;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;

use Cviebrock\EloquentSluggable\Sluggable;
use Laravel\Sanctum\HasApiTokens;

class GameServer extends Model implements
AuthenticatableContract,
AuthorizableContract
{
    use Sluggable, HasApiTokens, Authenticatable, Authorizable;

    /**
     * The name of the table.
     *
     * @var string
     */
    protected $table = 'game_servers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'game',
        'address',
        'game_port',
        'stream_port',
        'game_password',
        'rcon_port',
        'rcon_password'
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

    /*
     * Relationships
     */
    public function game()
    {
        return $this->belongsTo('App\Game');
    }

    public function eventTournamentMatchServer()
    {
        return $this->hasMany('App\EventTournamentMatchServer');
    }

    public function MatchMakingServers()
    {
        return $this->hasMany('App\MatchMakingServer', 'game_server_id', 'id');
    }

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
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
}
