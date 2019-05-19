<?php

namespace App;

use DB;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class PollOptionVote extends Model
{

    /**
     * The name of the table.
     *
     * @var string
     */
    protected $table = 'poll_option_votes';
    
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
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function pollOption()
    {
        return $this->belongsTo('App\PollOption', 'poll_option_id');
    }
}
