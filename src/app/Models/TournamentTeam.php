<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TournamentTeam extends Model
{
    /**
     * The name of the table.
     *
     * @var string
     */
    protected $table = 'tournament_teams';

    protected $fillable = [
        'name',
        'tournament_id',
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
    public function tournament()
    {
        return $this->belongsTo('App\Models\Tournament', 'tournament_id');
    }

    public function participants()
    {
        return $this->hasMany('App\Models\TournamentParticipant');
    }

    public function getParticipantCount()
    {
        return $this->participants()->count();
    }
}
