<?php

namespace App;

use DB;
use Auth;

use App\PollOptionVote as Vote;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class PollOption extends Model
{

    /**
     * The name of the table.
     *
     * @var string
     */
    protected $table = 'poll_options';
    
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

    public function poll()
    {
        return $this->belongsTo('App\Poll', 'poll_id');
    }

    public function votes()
    {
        return $this->hasMany('App\PollOptionVote', 'poll_option_id');
    }

    public function getTotalVotes()
    {
        return $this->votes->count();
    }

    public function vote()
    {
        // if (!$this->votes->pluck('user_id', Auth::id())) {
            $vote = new Vote;
            $vote->user_id = Auth::id();
            $vote->poll_option_id = $this->id;
            if (!$vote->save()) {
                return false;
            }
        // }
        return true;
    }

    public function abstain()
    {
        if (!$this->votes->pluck('user_id', Auth::id())->delete()) {
            return false;
        }
        return true;
    }
}
