<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GameSignupListParticipant extends Model
{
    /**
     * The name of the table.
     *
     * @var string
     */
    protected $table = 'game_signup_list_participant';

    protected $fillable = [
        'user_id',
        'game_signup_list_id',
        'game_signup_list_team_id'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = array(
        'created_at',
    );

    /*
     * Relationships
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User', foreignKey: 'user_id');
    }

    public function signupList()
    {
        return $this->belongsTo('App\Models\GameSignupList', 'game_signup_list_id');
    }

    public function team()
    {
        return $this->belongsTo('App\Models\GameSignupListTeam', 'game_signup_list_team_id');
    }
}
