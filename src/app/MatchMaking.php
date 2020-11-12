<?php

namespace App;

use DB;
use Auth;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

use Cviebrock\EloquentSluggable\Sluggable;

class MatchMaking extends Model
{
    
    /**
     * The name of the table.
     *
     * @var string
     */
    protected $table = 'matchmaking';


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

    public function teams()
    {
        return $this->hasMany('App\MatchMakingTeam', 'match_id');
    }




    public function game()
    {
        return $this->belongsTo('App\Game', 'game_id');
    }

    public function owner()
    {
        return $this->belongsTo('App\User', 'owner_id');
    }


    /*
    * oldest team
    */

    public function oldestTeam()
    {
        return $this->hasOne('App\MatchMakingTeam', 'match_id')->oldest();
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'id';
    }


     /**
     * Set Status
     * @param Boolean
     */
    public function setStatus($status)
    {
        $this->status = $status;
        if (!$this->save()) {
            return false;
        }
        return true;
    }

}
