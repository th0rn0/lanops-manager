<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GameSignupListTeam extends Model
{
    /**
     * The name of the table.
     *
     * @var string
     */
    protected $table = 'game_signup_list_participant';

    protected $fillable = [
        'name',
        'game_signup_list_id',
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
    public function signupList()
    {
        return $this->belongsTo('App\Models\GameSignupList', 'game_signup_list_id');
    }

    public function participants()
    {
        return $this->hasMany('App\Models\GameSignupListParticipant', 'game_signup_list_team_id');
    }
}
