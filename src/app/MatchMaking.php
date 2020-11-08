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
    public function firstteam()
    {
        return $this->belongsTo('App\MatchMakingTeam', 'first_team_id');
    }

    public function secondteam()
    {
        return $this->belongsTo('App\MatchMakingTeam', 'second_team_id');
    }


    public function game()
    {
        return $this->belongsTo('App\Game');
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
