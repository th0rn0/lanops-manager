<?php

namespace App\Models;

use Auth;

use Illuminate\Database\Eloquent\Model;

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
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function pollOption()
    {
        return $this->belongsTo('App\Models\PollOption', 'poll_option_id');
    }

    /**
     * Get New Votes
     * @param $type
     * @return PollOptionVote
     */
    public static function getNewVotes($type = 'all')
    {
        if (!$user = Auth::user()) {
            $type = 'all';
        }
        switch ($type) {
            case 'login':
                $votes = self::where('created_at', '>=', $user->last_login)->get();
                break;
            default:
                $votes = self::where('created_at', '>=', date('now - 1 day'))->get();
                break;
        }
        return $votes;
    }
}
