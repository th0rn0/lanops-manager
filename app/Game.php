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
        self::deleted(function($model){
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

    public static function getGameSelectArray($public_only = true)
    {
        $return[0] = 'None';
        foreach (Game::where('public', $public_only)->orderBy('name', 'ASC')->get() as $game) {
            $return[$game->id] = $game->name;
        }
        return $return;
    }
}