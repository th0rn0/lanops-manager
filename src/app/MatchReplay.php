<?php

namespace App;

use DB;
use Auth;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

use Cviebrock\EloquentSluggable\Sluggable;

class MatchReplay extends Model
{

    /**
     * The name of the table.
     *
     * @var string
     */
    protected $table = 'matchreplay';


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

    public function matchMakingMatch()
    {
        return $this->belongsTo('App\MatchMaking', 'matchmaking_id');
    }
}
