<?php

namespace App;

use DB;

use Illuminate\Database\Eloquent\Model;
use GuzzleHttp\Client;
use Reflex\Challonge\Challonge;
use App\EventParticipant;
use App\EventTournamentParticipant;
use Cviebrock\EloquentSluggable\Sluggable;


class EventTournament extends Model
{
    use sluggable;

    /**
     * The name of the table.
     *
     * @var string
     */
    protected $table = 'event_tournaments';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'event_id', 
        'challonge_tournament_id', 
        'challonge_tournament_url',
        'display_name', 
        'nice_name', 
        'game', 
        'format', 
        'bronze', 
        'team_size', 
        'description', 
        'allow_player_teams', 
        'status',
        'game_cover_image'
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

    public static function boot() {
        parent::boot();
        self::deleting(function($model){
            $challonge = new Challonge(env('CHALLONGE_API_KEY'));
            $response = $challonge->getTournament($model->challonge_tournament_id);
            if(!$response->delete()){
               return false;
            }
            return true;
        });
    }

    /*
     * Relationships
     */
    public function event()
    {
        return $this->belongsTo('App\Event');
    }
    public function tournamentParticipants()
    {
        return $this->hasMany('App\EventTournamentParticipant');
    }
    public function tournamentTeams()
    {
        return $this->hasMany('App\EventTournamentTeam');
    }

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }
    
    public function setStatus($status)
    {
        $challonge = new Challonge(env('CHALLONGE_API_KEY'));
        if($status == 'LIVE') {
            $tournament = $challonge->getTournament($this->challonge_tournament_id);
            if(!$tournament->start()){
                return FALSE;
            }
        }
        if($status == 'COMPLETE') {
            $tournament = $challonge->getTournament($this->challonge_tournament_id);
            if(!$tournament->finalize()){
                return FALSE;
            }
        }
        $this->status = $status;
        if(!$this->save()){
            return FALSE;
        }
        return TRUE;
    }

    public function getParticipant($event_participant_id)
    {
        return $this->tournamentParticipants()->where('event_participant_id', $event_participant_id)->first();
    }

    public function getChallongeMatches()
    {
        $challonge = new Challonge(env('CHALLONGE_API_KEY'));
        if(!$matches = $challonge->getMatches($this->challonge_tournament_id)){
            return FALSE;
        }
        return $matches;
    }
  
    public function getChallongeParticipants()
    {
        $challonge = new Challonge(env('CHALLONGE_API_KEY'));
        if(!$challonge_participants = $challonge->getParticipants($this->challonge_tournament_id)){
            return FALSE;
        }
        if($this->status == 'COMPLETE'){
            usort($challonge_participants, function($a, $b) { return strcmp($a->final_rank, $b->final_rank); });
        }
        return $challonge_participants;
    }

    public function getChallongeUrl()
    {
        $challonge = new Challonge(env('CHALLONGE_API_KEY'));
        if(!$tournament = $challonge->getTournament($this->challonge_tournament_id)){
            return FALSE;
        }
        $return_url = "https://" . env('CHALLONGE_URL') . "/" . $tournament->url;
        return $return_url;
    }

    public function getTeamsArray()
    {
        if(isset($this->tournamentTeams)){
            $team_array = array();
            foreach($this->tournamentTeams as $tournament_team){
                $team_array[$tournament_team->id] = $tournament_team->name;
            }
            return $team_array;
        }
        return null;
    }
}