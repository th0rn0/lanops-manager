<?php

namespace App\Models;

use DateTime;
use Auth;

use Spatie\WebhookServer\WebhookCall;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

use Cviebrock\EloquentSluggable\Sluggable;

class EventTimetable extends Model
{
    use Sluggable;

    protected $table = 'event_timetables';

    protected $hidden = array(
        'created_at',
        'updated_at'
    );

    protected static function boot()
    {
        parent::boot();

        $admin = false;
        if (Auth::user() && Auth::user()->getAdmin()) {
            $admin = true;
        }
        if (!$admin) {
            static::addGlobalScope('statusDraft', function (Builder $builder) {
                $builder->where('status', '!=', 'DRAFT');
            });
            static::addGlobalScope('statusPublished', function (Builder $builder) {
                $builder->where('status', 'PUBLISHED');
            });
        }

        self::updated(function ($model) {
            if (config('app.discord_bot_url') != '' && strtolower($model->status) == "published" && $model->event->discord_link_enabled) {
                WebhookCall::create()
                    ->url(config('app.discord_bot_url') . '/message/channel')
                    ->payload([
                        'channel_id' => $model->event->discord_channel_id,
                        'message' => "The timetable for " . $model->event->display_name . " is now live! " . config('app.url') . "/events/" . $model->event->slug . "#timetable"
                    ])
                    ->useSecret(config('app.discord_bot_secret'))
                    ->dispatch();
            }
            return true;
        });
    }
    
    /*
     * Relationships
     */
    public function event()
    {
        return $this->belongsTo('App\Models\Event');
    }
    public function data()
    {
        return $this->hasMany('App\Models\EventTimetableData');
    }

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

    /**
     * Get Available Time slots for timetable
     * @param  boolean $obj
     * @return Array
     */
    public function getAvailableTimes($obj = false)
    {
        $return = array();
        $endDate = new \DateTime($this->event->end);
        $startDate = new \DateTime($this->event->start);
        while ($startDate <= $endDate) {
            $return[$startDate->format('Y-m-d H:i:s')] = date(
                "D",
                strtotime($startDate->format('Y-m-d H:i:s'))
            ) . ' - ' .  date("H:i", strtotime($startDate->format('Y-m-d H:i:s')));
            $startDate->modify('+30 minutes');
        }
        if ($obj) {
            return json_decode(json_encode($return), false);
        }
        return $return;
    }

    public function getNext()
    {
        
    }
}
