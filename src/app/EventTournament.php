<?php

namespace App;

use DB;
use Cache;

use App\EventParticipant;
use App\EventTournamentParticipant;

use Illuminate\Database\Eloquent\Model;

use GuzzleHttp\Client;
use Lanops\Challonge\Challonge;
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
        'status'
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

    protected $casts = [
        'final_history' => 'array',
        'final_ratio' => 'array'
    ];

    public static function boot() {
        parent::boot();
        self::created(function ($model){
            if ($model->format != 'list') {
                $challonge = new Challonge(config('challonge.api_key'));
                $params = [
                    'tournament[name]'                    => $model->name,
                    'tournament[tournament_type]'         => strtolower($model->format),
                    'tournament[url]'                     => $model->challonge_tournament_url,
                    // 'tournament[subdomain]'               => env('CHALLONGE_SUBDOMAIN'),
                    'tournament[hold_third_place_match]'  =>@ ($model->allow_bronze ? true : false),
                    'tournament[show_rounds]'             => true,
                ];
                if (!$response = $challonge->createTournament($params)) {
                    $model->delete();
                    return false;
                }
                $model->challonge_tournament_id = $response->id;
                $model->save();
            }
            return true;
        });
        self::saved(function($model){
            if ($model->format != 'list') {
                // TODO - fire only when name is updated
                $challonge = new Challonge(config('challonge.api_key'));
                $challonge_tournament = $challonge->getTournament($model->challonge_tournament_id);
                $params = [
                    'tournament[name]' => $model->name
                ];
                if (!$response = $challonge_tournament->update($params)) {
                    return false;
                }
            }
            if ($model->status == 'COMPLETE' && $model->format != 'list' && !$model->api_complete) {
                foreach ($model->getStandings('desc', true)->final as $standings) {
                    $challonge = new Challonge(config('challonge.api_key'));
                    if (!$challonge_participants = $challonge->getParticipants($model->challonge_tournament_id)) {
                        return false;
                    }
                    if ($model->team_size == '1v1') {
                        $tournament_participant = $model->getParticipantByChallongeId($standings->id);
                        // TODO - Refactor
                        foreach ($challonge_participants as $challonge_participant) {
                            if ($challonge_participant->id == $tournament_participant->challonge_participant_id) {
                                $tournament_participant->final_rank = $challonge_participant->final_rank;
                            }
                        }
                        $tournament_participant->final_ratio = serialize([
                                                                'W' => $standings->win,
                                                                'L' => $standings->lose,
                                                                'T' => $standings->tie
                                                            ]);
                        $tournament_participant->final_score = $standings->pts;
                        $final_history = array();
                        foreach ($standings->history as $game) {
                            array_push($final_history, $game);
                        }
                        $tournament_participant->final_history = serialize($final_history);
                        $tournament_participant->save();
                    }
                    if ($model->team_size != '1v1') {
                        $tournament_team = $model->getTeamByChallongeId($standings->id);
                        // TODO - Refactor
                        foreach ($challonge_participants as $challonge_participant) {
                            if ($challonge_participant->id == $tournament_team->challonge_participant_id) {
                                $tournament_team->final_rank = $challonge_participant->final_rank;
                            }
                        }
                        $tournament_team->final_ratio = serialize([
                                        'W' => $standings->win,
                                        'L' => $standings->lose,
                                        'T' => $standings->tie
                                    ]);
                        $tournament_team->final_score = $standings->pts;
                        $final_history = array();
                        foreach ($standings->history as $game) {
                            array_push($final_history, $game);
                        }
                        $tournament_team->final_history = serialize($final_history);
                        $tournament_team->save();
                    }
                    $model->api_complete = true;
                    $model->save();
                }
            }
            return true;
        });
        self::deleting(function($model){
            if ($model->format != 'list') {
                $challonge = new Challonge(config('challonge.api_key'));
                $response = $challonge->getTournament($model->challonge_tournament_id);
                if (!$response->delete()) {
                   return false;
                }
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
    public function game()
    {
        return $this->belongsTo('App\Game');
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
        $challonge = new Challonge(config('challonge.api_key'));
        if ($status == 'LIVE') {
            if ($this->tournamentTeams) {
                foreach ($this->tournamentTeams as $team) {
                    if ($team->tournamentParticipants->isEmpty()) {
                        $team->delete();
                    }
                }
            }            
            if ($this->format != 'list') {
                $tournament = $challonge->getTournament($this->challonge_tournament_id);
                try {
                    $tournament->start();
                } catch (\Exception $e) {
                    return false;
                }
            }
        }
        if ($status == 'COMPLETE') {
            if ($this->format != 'list') {
                $tournament = $challonge->getTournament($this->challonge_tournament_id);
                try {
                    $tournament->finalize();
                } catch (\Exception $e) {
                    return false;
                }
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
     * Get Tournament Participant By Challonge ID
     * @param  $challonge_participant_id
     * @return EventTournamentParticipant
     */
    public function getParticipantByChallongeId($challonge_participant_id)
    {
        return $this->tournamentParticipants()->where('challonge_participant_id', $challonge_participant_id)->first();
    }

     /**
     * Get Tournament Team By Challonge ID
     * @param  $challonge_participant_id
     * @return EventTournamentParticipant
     */
    public function getTeamByChallongeId($challonge_participant_id)
    {
        return $this->tournamentTeams()->where('challonge_participant_id', $challonge_participant_id)->first();
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

    /**
     * Get Matches
     * @param  boolean $obj
     * @return Array|Object
     */
    public function getMatches($obj = false)
    {
        $tournament_matches = Cache::get($this->challonge_tournament_id . "_matches", function () {
            $challonge = new Challonge(config('challonge.api_key'));
            $matches = $challonge->getMatches($this->challonge_tournament_id);
            Cache::forever($this->challonge_tournament_id . "_matches", $matches);
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

    /**
     * Get Standings
     * @param  string $order
     * @param  boolean $obj
     * @param  boolean $retroactive - For Legacy
     * @return Array|Object
     */
    public function getStandings($order = null, $obj = false, $retroactive = false)
    {
        $tournament_standings = Cache::get($this->challonge_tournament_id . "_standings", function() use ($retroactive) {
            if ($this->status == 'COMPLETE' && $this->api_complete && $this->format != 'list') {
                $standings['progress'] = 100;
                $standings_array = array();
                if ($this->team_size != '1v1') {
                    $participants = $this->tournamentTeams;
                }
                if ($this->team_size == '1v1') {
                    $participants = $this->tournamentParticipants;
                }
                foreach ($participants as $participant) {
                    $ratio = unserialize($participant->final_ratio);
                    $history = unserialize($participant->final_history);
                    $params = [
                        'win' => $ratio['W'],
                        'lose' => $ratio['L'],
                        'tie' => $ratio['T'],
                        'pts' => $participant->final_score,
                        'history' => $history,
                        'name' => ($this->team_size != '1v1' ? $participant->name : $participant->eventParticipant->user->username),
                        'id' => $participant->challonge_participant_id
                    ];
                    array_push($standings_array, $params);
                }
                $standings['final'] = collect($standings_array);
            }
            ## Pull LIVE standings from Challonge when tournament is in progress
            if ($retroactive || ($this->status != 'COMPLETE' && !$this->api_complete && $this->format != 'list')) {
                $challonge = new Challonge(config('challonge.api_key'));
                $standings = $challonge->getStandings($this->challonge_tournament_id);
            }

            Cache::forever($this->challonge_tournament_id . "_standings", $standings);
            return $standings;
        });
        if ($order == 'asc') {
            $standings = $tournament_standings['final'];
            $tournament_standings['final'] = $standings->sortBy('pts');
        }
        if ($order == 'desc') {
            $standings = $tournament_standings['final'];
            $tournament_standings['final'] = $standings->sortByDesc('pts');
        }
        if ($obj) {
            return json_decode(json_encode($tournament_standings), FALSE);
        }
        return $tournament_standings;
    }

    /**
     * Get Next Matches
     * @param  integer $limit
     * @param  boolean $obj
     * @return Array|Object
     */
    public function getNextMatches($limit = 0, $obj = false)
    {
        $tournament_matches = Cache::get($this->challonge_tournament_id . "_matches", function () {
            $challonge = new Challonge(config('challonge.api_key'));
            $matches = $challonge->getMatches($this->challonge_tournament_id);
            Cache::forever($this->challonge_tournament_id . "_matches", $matches);
            return $matches;
        });
        $next_matches = array();
        foreach ($tournament_matches as $match) {
            if ($match->state == 'open') {
                $next_matches[] = $match;
            }
            if (count($next_matches) == $limit && $limit != 0) {
                break;
            }
        }
        if ($obj) {
            return json_decode(json_encode($next_matches), FALSE);
        }
        return $next_matches;
    }

    /**
     * Update Match
     * @param  string $match_id
     * @param  string $player1_score
     * @param  string $player2_score
     * @param  string $player_winner_verify
     * @return Array|Object
     */
    public function updateMatch($match_id, $player1_score, $player2_score, $player_winner_verify = null)
    {
        // TODO - add support for multiple sets
        $challonge = new Challonge(config('challonge.api_key'));
        $match = $challonge->getMatch($this->challonge_tournament_id, $match_id);

        if ($player1_score > $player2_score) {
            $player_winner_id = $match->player1_id;
        }
        if ($player2_score > $player1_score) {
            $player_winner_id = $match->player2_id;
        }
        if ($player_winner_verify == 'player1') {
            $player_winner_id = $match->player1_id;
        }
        if ($player_winner_verify == 'player2') {
            $player_winner_id = $match->player2_id;
        }
        $params = [
            'match' => [
                'scores_csv' => $player1_score . '-' . $player2_score,
                'winner_id' => $player_winner_id
            ]
        ];
        if (!$response = $match->update($params)) {
            return false;
        }
        # Update Cache
        Cache::forget($this->challonge_tournament_id . "_matches");
        Cache::forget($this->challonge_tournament_id . "_standings");
        $this->getMatches();
        $this->getStandings();
        return $response;
    }

    /**
     * Update all scores Retoractively - This is for Legacy
     * @return Array|Object
     */
    public static function getAllScoresRetroActively()
    {
        $count = 0;
        foreach (EventTournament::all() as $model) {
            if ($model->status == 'COMPLETE' && $model->format != 'list' && !$model->api_complete) {
                foreach ($model->getStandings('desc', true, true)->final as $standings) {
                    $challonge = new Challonge(config('challonge.api_key'));
                    if (!$challonge_participants = $challonge->getParticipants($model->challonge_tournament_id)) {
                        return false;
                    }
                    if ($model->team_size == '1v1') {
                        $tournament_participant = $model->getParticipantByChallongeId($standings->id);
                        // TODO - Refactor
                        foreach ($challonge_participants as $challonge_participant) {
                            if ($challonge_participant->id == $tournament_participant->challonge_participant_id) {
                                $tournament_participant->final_rank = $challonge_participant->final_rank;
                            }
                        }
                        $tournament_participant->final_ratio = serialize([
                                                                'W' => $standings->win,
                                                                'L' => $standings->lose,
                                                                'T' => $standings->tie
                                                            ]);
                        $tournament_participant->final_score = $standings->pts;
                        $final_history = array();
                        foreach ($standings->history as $game) {
                            array_push($final_history, $game);
                        }
                        $tournament_participant->final_history = serialize($final_history);
                        $tournament_participant->save();
                    }
                    if ($model->team_size != '1v1') {
                        $tournament_team = $model->getTeamByChallongeId($standings->id);
                        // TODO - Refactor
                        foreach ($challonge_participants as $challonge_participant) {
                            if ($challonge_participant->id == $tournament_team->challonge_participant_id) {
                                $tournament_team->final_rank = $challonge_participant->final_rank;
                            }
                        }
                        $tournament_team->final_ratio = serialize([
                                        'W' => $standings->win,
                                        'L' => $standings->lose,
                                        'T' => $standings->tie
                                    ]);
                        $tournament_team->final_score = $standings->pts;
                        $final_history = array();
                        foreach ($standings->history as $game) {
                            array_push($final_history, $game);
                        }
                        $tournament_team->final_history = serialize($final_history);
                        $tournament_team->save();
                    }
                    $model->api_complete = true;
                    $model->save();
                }
                $count++;
            }
        }
        dd('DUN');
    }
}