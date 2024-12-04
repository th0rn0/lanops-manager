<?php

namespace App\Models;

use Auth;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

use Cviebrock\EloquentSluggable\Sluggable;

class Tournament extends Model
{
    use Sluggable;

    /**
     * The name of the table.
     *
     * @var string
     */
    protected $table = 'tournaments';

    protected $fillable = [
        'name',
        'event_id',
        'slug',
        'team_size',
        'status'
    ];

    public static $statusDraft = 'DRAFT';

    public static $statusOpen = 'OPEN';

    public static $statusClosed = 'CLOSED';

    public static $statusLive = 'LIVE';

    public static $statusComplete = 'COMPLETE';


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
        self::creating(function ($model) {
            $model->status = Tournament::$statusDraft;
        });

        $admin = false;
        if (Auth::user() && Auth::user()->getAdmin()) {
            $admin = true;
        }
        if (!$admin) {
            static::addGlobalScope('statusDraft', function (Builder $builder) {
                $builder->where('status', '!=', Tournament::$statusDraft);
            });
            // static::addGlobalScope('statusPublished', function (Builder $builder) {
            //     $builder->where('status', 'PUBLISHED');
            // });
        }
    }

    /*
     * Relationships
     */
    public function event()
    {
        return $this->belongsTo('App\Models\Event', 'event_id');
    }

    public function participants()
    {
        return $this->hasMany('App\Models\TournamentParticipant');
    }

    public function teams()
    {
        return $this->hasMany('App\Models\TournamentTeam');
    }

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
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
    
    public function isComplete()
    {
        return $this->status == Tournament::$statusComplete;
    }

    public static function getStatusArray()
    {
        return [
            Tournament::$statusDraft    => Tournament::$statusDraft,
            Tournament::$statusOpen     => Tournament::$statusOpen,
            Tournament::$statusClosed   => Tournament::$statusClosed,
            Tournament::$statusLive     => Tournament::$statusLive,
            Tournament::$statusComplete => Tournament::$statusComplete,
        ];
    }

    public function isTeamBased()
    {
        return $this->team_size > 0;
    }

    public function getParticipantByUser(User $user)
    {
        return $this->participants()->where('user_id', $user->id)->first();
    }

    public function isUserSignedUp(User $user)
    {
        return $this->participants()->where('user_id', $user->id)->get()->count() > 0;
    }

    public function signupsOpen()
    {
        return $this->status == Tournament::$statusOpen;
    }

    public function hasParticipants()
    {
        return $this->participants()->count() > 0;
    }

    public function hasEvent()
    {
        return $this->event()->count() > 0;
    }

    public function hasTeams()
    {
        return $this->team_size > 0;
    }
}
