<?php

namespace App\Models;

use App\Models\TournamentTeam;

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

    public static function boot()
    {
        parent::boot();
        self::deleted(function ($model) {
            if ($model->getOriginal('tournament_team_id') != null && $team = TournamentTeam::where('id', $model->getOriginal('tournament_team_id'))->first()) {
                if ($team->participants()->count() == 0) {
                    $team->delete();
                }
            }
        });
    }
    /*
     * Relationships
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User', foreignKey: 'user_id');
    }

    public function tournament()
    {
        return $this->belongsTo('App\Models\Tournament', 'tournament_id');
    }

    public function team()
    {
        return $this->belongsTo('App\Models\TournamentTeam', 'tournament_team_id');
    }

    public function getSeat()
    {
        if (!$this->tournament->hasEvent()) {
            return null;
        }
        return $this->tournament->event->eventParticipants()->where('user_id', $this->user_id)->first()->seat;
    }
}
