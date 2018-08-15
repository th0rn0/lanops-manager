<?php

namespace App;

use DB;
use Cache;

use App\EventParticipant;
use App\EventTournamentParticipant;

use Illuminate\Database\Eloquent\Model;

use GuzzleHttp\Client;
use Reflex\Challonge\Challonge;
use Cviebrock\EloquentSluggable\Sluggable;
use Carbon\Carbon;


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
        'game_cover_image_path'
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
            if (!$response->delete()) {
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

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }
    
    /**
     * Set Status
     * @param Boolean
     */
    public function setStatus($status)
    {
        $challonge = new Challonge(env('CHALLONGE_API_KEY'));
        if ($status == 'LIVE') {
            $tournament = $challonge->getTournament($this->challonge_tournament_id);
            try {
                $tournament->start();
            } catch (\Exception $e) {
                return false;
            }
        }
        if ($status == 'COMPLETE') {
            $tournament = $challonge->getTournament($this->challonge_tournament_id);
            try {
                $tournament->finalize();
            } catch (\Exception $e) {
                return false;
            }
        }
        $this->status = $status;
        if (!$this->save()) {
            return false;
        }
        return true;
    }

    /**
     * Get Tournament Participant
     * @param  $event_participant_id
     * @return EventTournamentParticipant
     */
    public function getParticipant($event_participant_id)
    {
        return $this->tournamentParticipants()->where('event_participant_id', $event_participant_id)->first();
    }

    /**
     * Get Tournament Participant
     * @param  $challonge_participant_id
     * @return EventTournamentParticipant
     */
    public function getParticipantByChallongeId($challonge_participant_id)
    {
        return $this->tournamentParticipants()->where('challonge_participant_id', $challonge_participant_id)->first();
    }


    // DEBUG - NEEDED?
    /**
     * Get Matches from Challonge
     * @return JSON|Boolean
     */
    // public function getChallongeMatches()
    // {
    //     $challonge = new Challonge(env('CHALLONGE_API_KEY'));
    //     if (!$matches = $challonge->getMatches($this->challonge_tournament_id)) {
    //         return false;
    //     }
    //     return $matches;
    // }
  
    /**
     * Get Participants from Challonge
     * @return JSON|Boolean
     */
    public function getChallongeParticipants()
    {
        $challonge = new Challonge(env('CHALLONGE_API_KEY'));
        if (!$challonge_participants = $challonge->getParticipants($this->challonge_tournament_id)) {
            return false;
        }
        if ($this->status == 'COMPLETE') {
            usort($challonge_participants, function($a, $b) { return strcmp($a->final_rank, $b->final_rank); });
        }
        return $challonge_participants;
    }

    /**
     * Get Challonge URL
     * @return String|Boolean
     */
    public function getChallongeUrl()
    {
        $challonge = new Challonge(env('CHALLONGE_API_KEY'));
        if (!$tournament = $challonge->getTournament($this->challonge_tournament_id)) {
            return false;
        }
        return "https://" . env('CHALLONGE_URL') . "/" . $tournament->url;
    }

    /**
     * Get Teams
     * @param  boolean $obj
     * @return Array|Object
     */
    public function getTeams($obj = false)
    {
        if (!isset($this->tournamentTeams)) {
            return null;
        }
        $return = array();
        foreach ($this->tournamentTeams as $tournament_team) {
            $return[$tournament_team->id] = $tournament_team->name;
        }
        if ($obj) {
            return json_decode(json_encode($return), FALSE);
        }
        return $return;
    }

    public function getMatches($obj = false)
    {
        $tournament_matches = Cache::get($this->challonge_tournament_id + '-matches', function () {
            $challonge = new Challonge(env('CHALLONGE_API_KEY'));
            $matches = $challonge->getMatches($this->challonge_tournament_id);
            Cache::put($this->challonge_tournament_id + '-matches', $matches, Carbon::now()->addMinutes(2));
            return $matches;
        });
        $return = array();
        foreach ($tournament_matches as $match) {
            $return[$match->round][$match->suggested_play_order] = $match;
        }
        if ($obj) {
            return json_decode(json_encode($return), FALSE);
        }
        return $return;
    }
}