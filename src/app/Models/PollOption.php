<?php

namespace App\Models;

use Auth;

use App\Models\PollOptionVote as Vote;

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
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function poll()
    {
        return $this->belongsTo('App\Models\Poll', 'poll_id');
    }

    public function votes()
    {
        return $this->hasMany('App\Models\PollOptionVote', 'poll_option_id');
    }

    public function getTotalVotes()
    {
        return $this->votes->count();
    }

    public function vote()
    {
        if (!$this->hasVoted()) {
            $vote = new Vote();
            $vote->user_id = Auth::id();
            $vote->poll_option_id = $this->id;
            if (!$vote->save()) {
                return false;
            }
        }
        return true;
    }

    public function abstain()
    {
        if ($this->hasVoted() && !$this->votes->where('user_id', Auth::id())->first()->delete()) {
            return false;
        }
        return true;
    }

    public function hasVoted()
    {
        if ($this->votes->where('user_id', Auth::id())->count() <= 0) {
            return false;
        }
        return true;
    }

    public function getPercentage()
    {
        if ($this->poll->getTotalVotes() == 0) {
            return 0;
        }
        return ($this->getTotalVotes() / $this->poll->getTotalVotes()) * 100;
    }
}
