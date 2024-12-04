<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TournamentParticipant extends Model
{
    /**
     * The name of the table.
     *
     * @var string
     */
    protected $table = 'tournament_participants';

    protected $fillable = [
        'user_id',
        'tournament_id',
        'tournament_team_id'
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
        return $this->belongsTo('App\Models\Tournament', 'tournament_id');
    }

    public function team()
    {
        return $this->belongsTo('App\Models\TournamentTeam', 'tournament_team_id');
    }
}
