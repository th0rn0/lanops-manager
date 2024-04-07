<?php

namespace App\Models;

use Auth;
use App\Models\PollOption;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

use Spatie\WebhookServer\WebhookCall;
use Cviebrock\EloquentSluggable\Sluggable;

class Poll extends Model
{
    use Sluggable;

    /**
     * The name of the table.
     * @var string
     */
    protected $table = 'polls';
    
    /**
     * The attributes excluded from the model's JSON form.
     * @var array
     */
    protected $hidden = array(
        'created_at',
        'updated_at'
    );

    protected static function boot()
    {
        parent::boot();

        self::updated(function ($model) {
            if (config('app.discord_bot_url') != '' && !is_null($model->event_id) && $model->status == "PUBLISHED") {
                WebhookCall::create()
                    ->url(config('app.discord_bot_url') . '/message/channel')
                    ->payload([
                        'channel_id' => $model->event->discord_channel_id,
                        'message' => "We have a new poll for " . $model->event->display_name . " - go vote now - " . config('app.url') . "/polls/" . $model->slug
                    ])
                    ->useSecret(config('app.discord_bot_secret'))
                    ->dispatch();
            }
        });

        $admin = false;
        if (Auth::user() && Auth::user()->getAdmin()) {
            $admin = true;
        }
        if (!$admin) {
            static::addGlobalScope('statusDraft', function (Builder $builder) {
                $builder->where('status', '!=', 'DRAFT');
            });
            static::addGlobalScope('statusPreview', function (Builder $builder) {
                $builder->where('status', '!=', 'PREVIEW');
            });
            static::addGlobalScope('statusPublished', function (Builder $builder) {
                $builder->where('status', 'PUBLISHED');
            });
        }
    }

    /*
     * Relationships
     */
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function options()
    {
        return $this->hasMany('App\Models\PollOption', 'poll_id');
    }

    public function event()
    {
        return $this->belongsTo('App\Models\Event', 'event_id');
    }

    /**
     * Return the sluggable configuration array for this model.
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
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Add Poll Option to the Database.
     * @return string
     */
    public function addOption($option)
    {
        if (!empty(trim($option))) {
            $pollOption = new PollOption();
            $pollOption->name = trim($option);
            $pollOption->user_id = Auth::id();
            $pollOption->poll_id = $this->id;
            if (!$pollOption->save()) {
                return false;
            }
        }
        return true;
    }
    
    /**
     * Get Total Votes
     * @return int
     */
    public function getTotalVotes()
    {
        $total = 0;
        foreach ($this->options as $option) {
            $total += $option->getTotalVotes();
        }
        return $total;
    }

    /**
     * End the Poll
     * @return boolean
     */
    public function endPoll()
    {
        $this->end = date("Y-m-d H:i:s");
        if (!$this->save()) {
            return false;
        }
        return true;
    }

    /**
     * See if the Poll has ended
     * @return boolean
     */
    public function hasEnded()
    {
        if ($this->end == null || ($this->end == "0000-00-00 00:00:00" || $this->end >= date("Y-m-d H:i:s"))) {
            return false;
        }
        return true;
    }

    /**
     * See if the Poll has started
     * @return boolean
     */
    public function hasStarted()
    {
        if ($this->start != "0000-00-00 00:00:00" || $this->start <= date("Y-m-d H:i:s")) {
            return false;
        }
        return true;
    }

    public function sortOptions()
    {
        $this->options = $this->options->sortBy(function ($option, $key) {
            return $option->getTotalVotes();
        });
    }
}
