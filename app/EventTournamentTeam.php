<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use GuzzleHttp\Client;
use Lanops\Challonge\Challonge;

class EventTournamentTeam extends Model
{
    /**
     * The name of the table.
     *
     * @var string
     */
    protected $table = 'event_tournament_teams';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'event_tournament_id', 
        'challonge_participant_id',
        'name'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = array(
        'created_at',
        'updated_at'
    );
   
    public static function boot()
    {
        parent::boot();
        self::created(function($model){
            $challonge = new Challonge(env('CHALLONGE_API_KEY'));
            $tournament = $challonge->getTournament($model->eventTournament->challonge_tournament_id);
            if (!$response = $tournament->addParticipant(['participant[name]' => $model->name])) {
                $model->delete();
                return false;
            }
            $model->challonge_participant_id = $response->id;
            $model->save();
            return true;
        });
        self::deleting(function($model){
            $challonge = new Challonge(env('CHALLONGE_API_KEY'));
            $participant = $challonge->getParticipant($model->eventTournament->challonge_tournament_id, $model->challonge_participant_id);
            if (!$response = $participant->delete()) {
                return false;
            }
            return true;
        });
    }

    /*
     * Relationships
     */
    public function eventTournament()
    {
        return $this->belongsTo('App\EventTournament');
    }
    public function tournamentParticipants()
    {
        return $this->hasMany('App\EventTournamentParticipant');
    }
}