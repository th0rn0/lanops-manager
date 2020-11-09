<?php

namespace App;

use DB;
use Cache;
use Settings;
use Colors;

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
        'rules',
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

    public static function boot()
    {
        parent::boot();
        self::created(function ($model) {
            if ($model->format != 'list') {
                $challonge = new Challonge(config('challonge.api_key'));
                $params = [
                    'tournament[name]'                    => $model->name,
                    'tournament[tournament_type]'         => strtolower($model->format),
                    'tournament[url]'                     => $model->challonge_tournament_url,
                    'tournament[hold_third_place_match]'  => @ ($model->allow_bronze ? true : false),
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
        self::saved(function ($model) {
            if ($model->format != 'list') {
                // TODO - fire only when name is updated
                $challonge = new Challonge(config('challonge.api_key'));
                $challongeTournament = $challonge->getTournament($model->challonge_tournament_id);
                $params = [
                    'tournament[name]' => $model->name
                ];
                if (!$response = $challongeTournament->update($params)) {
                    return false;
                }
            }
            if ($model->status == 'COMPLETE' && $model->format != 'list' && !$model->api_complete) {
                foreach ($model->getStandings('desc', true)->final as $standings) {
                    $challonge = new Challonge(config('challonge.api_key'));
                    if (!$challongeParticipants = $challonge->getParticipants($model->challonge_tournament_id)) {
                        return false;
                    }
                    if ($model->team_size == '1v1') {
                        $tournamentParticipant = $model->getParticipantByChallongeId($standings->id);
                        // TODO - Refactor
                        foreach ($challongeParticipants as $challongeParticipant) {
                            if ($challongeParticipant->id == $tournamentParticipant->challonge_participant_id) {
                                $tournamentParticipant->final_rank = $challongeParticipant->final_rank;
                            }
                        }
                        $tournamentParticipant->final_ratio = serialize([
                                                                'W' => $standings->win,
                                                                'L' => $standings->lose,
                                                                'T' => $standings->tie
                                                            ]);
                        $tournamentParticipant->final_score = $standings->pts;
                        $finalHistory = array();
                        foreach ($standings->history as $game) {
                            array_push($finalHistory, $game);
                        }
                        $tournamentParticipant->final_History = serialize($finalHistory);
                        $creditAmount = 0;
                        if (Settings::isCreditEnabled() && !$tournamentParticipant->credit_applied) {
                            $creditAmount += Settings::getCreditTournamentParticipation();
                            switch ($tournamentParticipant->final_rank) {
                                case '1':
                                    $creditAmount += Settings::getCreditTournamentFirst();
                                    break;
                                case '2':
                                    $creditAmount += Settings::getCreditTournamentSecond();
                                    break;
                                case '3':
                                    $creditAmount += Settings::getCreditTournamentThird();
                                    break;
                            }
                            $tournamentParticipant->eventParticipant->user->editCredit($creditAmount, false, 'Tournament ' . $model->name . ' 1st Place');
                        }
                        $tournamentParticipant->credit_applied = true;
                        $tournamentParticipant->save();
                    }
                    if ($model->team_size != '1v1') {
                        $tournamentTeam = $model->getTeamByChallongeId($standings->id);
                        // TODO - Refactor
                        foreach ($challongeParticipants as $challongeParticipant) {
                            if ($challongeParticipant->id == $tournamentTeam->challonge_participant_id) {
                                $tournamentTeam->final_rank = $challongeParticipant->final_rank;
                            }
                        }
                        $tournamentTeam->final_ratio = serialize([
                                        'W' => $standings->win,
                                        'L' => $standings->lose,
                                        'T' => $standings->tie
                                    ]);
                        $tournamentTeam->final_score = $standings->pts;
                        $finalHistory = array();
                        foreach ($standings->history as $game) {
                            array_push($finalHistory, $game);
                        }
                        $tournamentTeam->final_History = serialize($finalHistory);
                        if (Settings::isCreditEnabled()) {
                            foreach ($tournamentTeam->tournamentParticipants as $tournamentParticipant) {
                                $creditAmount = 0;
                                if (!$tournamentParticipant->credit_applied) {
                                    $creditAmount += Settings::getCreditTournamentParticipation();
                                    switch ($tournamentParticipant->final_rank) {
                                        case '1':
                                            $creditAmount += Settings::getCreditTournamentFirst();
                                            break;
                                        case '2':
                                            $creditAmount += Settings::getCreditTournamentSecond();
                                            break;
                                        case '3':
                                            $creditAmount += Settings::getCreditTournamentThird();
                                            break;
                                    }
                                    $tournamentParticipant->eventParticipant->user->editCredit($creditAmount, false, 'Tournament ' . $model->name . ' 1st Place');
                                }
                                $tournamentParticipant->credit_applied = true;
                                $tournamentParticipant->save();
                            }
                        }
                        $tournamentTeam->save();
                    }
                    $model->api_complete = true;
                    $model->save();
                }
            }
            return true;
        });
        self::deleting(function ($model) {
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
     * @param  $eventParticipantId
     * @return EventTournamentParticipant
     */
    public function getParticipant($eventParticipantId)
    {
        return $this->tournamentParticipants()->where('event_participant_id', $eventParticipantId)->first();
    }

    /**
     * Get Tournament Participant By Challonge ID
     * @param  $challongeParticipantId
     * @return EventTournamentParticipant
     */
    public function getParticipantByChallongeId($challongeParticipantId)
    {
        return $this->tournamentParticipants()->where('challonge_participant_id', $challongeParticipantId)->first();
    }

     /**
     * Get Tournament Team By Challonge ID
     * @param  $challongeParticipantId
     * @return EventTournamentParticipant
     */
    public function getTeamByChallongeId($challongeParticipantId)
    {
        return $this->tournamentTeams()->where('challonge_participant_id', $challongeParticipantId)->first();
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
        foreach ($this->tournamentTeams as $tournamentTeam) {
            $return[$tournamentTeam->id] = $tournamentTeam->name;
        }
        if ($obj) {
            return json_decode(json_encode($return), false);
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
        $tournamentMatches = Cache::get($this->challonge_tournament_id . "_matches", function () {
            $challonge = new Challonge(config('challonge.api_key'));
            $matches = $challonge->getMatches($this->challonge_tournament_id);
            Cache::forever($this->challonge_tournament_id . "_matches", $matches);
            return $matches;
        });
        $return = array();
        foreach ($tournamentMatches as $match) {
            $return[$match->round][$match->suggested_play_order] = $match;
        }
        if ($obj) {
            return json_decode(json_encode($return), false);
        }
        return $return;
    }

    /**
     * Get Matches
     * @param int $challongeMatchId
     * @param boolean $obj
     * @return Array|Object
     */
    public function getMatch(int $challongeMatchId, $obj = false)
    {
        $tournamentMatches = Cache::get($this->challonge_tournament_id . "_matches", function () {
            $challonge = new Challonge(config('challonge.api_key'));
            $matches = $challonge->getMatches($this->challonge_tournament_id);
            Cache::forever($this->challonge_tournament_id . "_matches", $matches);
            return $matches;
        });

        foreach ($tournamentMatches as $match) {
            if($match->id == $challongeMatchId)
            {
                if ($obj) {
                    return json_decode(json_encode($match), false);
                }
                return $match;
            }
        }

        return false;
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
        $tournamentStandings = Cache::get(
            $this->challonge_tournament_id . "_standings",
            function () use ($retroactive) {
                if ($this->status == 'COMPLETE' && $this->api_complete && $this->format != 'list') {
                    $standings['progress'] = 100;
                    $standingsArray = array();
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
                        'name' => (
                            $this->team_size != '1v1' ?
                                $participant->name : $participant->eventParticipant->user->username
                            ),
                            'id' => $participant->challonge_participant_id
                        ];
                        array_push($standingsArray, $params);
                    }
                    $standings['final'] = collect($standingsArray);
                }
                ## Pull LIVE standings from Challonge when tournament is in progress
                if ($retroactive || ($this->status != 'COMPLETE' && !$this->api_complete && $this->format != 'list')) {
                    $challonge = new Challonge(config('challonge.api_key'));
                    $standings = $challonge->getStandings($this->challonge_tournament_id);
                }

                Cache::forever($this->challonge_tournament_id . "_standings", $standings);
                return $standings;
            }
        );
        if ($order == 'asc') {
            $standings = $tournamentStandings['final'];
            $tournamentStandings['final'] = $standings->sortBy('pts');
        }
        if ($order == 'desc') {
            $standings = $tournamentStandings['final'];
            $tournamentStandings['final'] = $standings->sortByDesc('pts');
        }
        if ($obj) {
            return json_decode(json_encode($tournamentStandings), false);
        }
        return $tournamentStandings;
    }

    /**
     * Get Next Matches
     * @param  integer $limit
     * @param  boolean $obj
     * @return Array|Object
     */
    public function getNextMatches($limit = 0, $obj = false)
    {
        $tournamentMatches = Cache::get($this->challonge_tournament_id . "_matches", function () {
            $challonge = new Challonge(config('challonge.api_key'));
            $matches = $challonge->getMatches($this->challonge_tournament_id);
            Cache::forever($this->challonge_tournament_id . "_matches", $matches);
            return $matches;
        });
        $nextMatches = array();
        foreach ($tournamentMatches as $match) {
            if ($match->state == 'open') {
                $nextMatches[] = $match;
            }
            if (count($nextMatches) == $limit && $limit != 0) {
                break;
            }
        }
        if ($obj) {
            return json_decode(json_encode($nextMatches), false);
        }
        return $nextMatches;
    }

    /**
     * Update Match
     * @param  string $matchId
     * @param  string $player1Score
     * @param  string $player2Score
     * @param  string $player_winner_verify
     * @return Array|Object
     */
    public function updateMatch($matchId, $player1Score, $player2Score, $playerWinnerVerify = null)
    {
        // TODO - add support for multiple sets
        $challonge = new Challonge(config('challonge.api_key'));
        $match = $challonge->getMatch($this->challonge_tournament_id, $matchId);

        if ($player1Score > $player2Score) {
            $playerWinnerId = $match->player1_id;
        }
        if ($player2Score > $player1Score) {
            $playerWinnerId = $match->player2_id;
        }
        if ($playerWinnerVerify == 'player1') {
            $playerWinnerId = $match->player1_id;
        }
        if ($playerWinnerVerify == 'player2') {
            $playerWinnerId = $match->player2_id;
        }
        $params = [
            'match' => [
                'scores_csv' => $player1Score . '-' . $player2Score,
                'winner_id' => $playerWinnerId
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
                    if (!$challongeParticipants = $challonge->getParticipants($model->challonge_tournament_id)) {
                        return false;
                    }
                    if ($model->team_size == '1v1') {
                        $tournamentParticipant = $model->getParticipantByChallongeId($standings->id);
                        // TODO - Refactor
                        foreach ($challongeParticipants as $challongeParticipant) {
                            if ($challongeParticipant->id == $tournamentParticipant->challonge_participant_id) {
                                $tournamentParticipant->final_rank = $challongeParticipant->final_rank;
                            }
                        }
                        $tournamentParticipant->final_ratio = serialize([
                                                                'W' => $standings->win,
                                                                'L' => $standings->lose,
                                                                'T' => $standings->tie
                                                            ]);
                        $tournamentParticipant->final_score = $standings->pts;
                        $finalHistory = array();
                        foreach ($standings->history as $game) {
                            array_push($finalHistory, $game);
                        }
                        $tournamentParticipant->final_history = serialize($finalHistory);
                        $tournamentParticipant->save();
                    }
                    if ($model->team_size != '1v1') {
                        $tournamentTeam = $model->getTeamByChallongeId($standings->id);
                        // TODO - Refactor
                        foreach ($challongeParticipants as $challongeParticipant) {
                            if ($challongeParticipant->id == $tournamentTeam->challonge_participant_id) {
                                $tournamentTeam->final_rank = $challongeParticipant->final_rank;
                            }
                        }
                        $tournamentTeam->final_ratio = serialize([
                                        'W' => $standings->win,
                                        'L' => $standings->lose,
                                        'T' => $standings->tie
                                    ]);
                        $tournamentTeam->final_score = $standings->pts;
                        $finalHistory = array();
                        foreach ($standings->history as $game) {
                            array_push($finalHistory, $game);
                        }
                        $tournamentTeam->final_history = serialize($finalHistory);
                        $tournamentTeam->save();
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
