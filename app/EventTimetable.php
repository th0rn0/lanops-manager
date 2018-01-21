<?php

namespace App;

use DateTime;
use Auth;
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
        if(!$admin) {
            static::addGlobalScope('statusDraft', function (Builder $builder) {
                $builder->where('status', '!=', 'DRAFT');
            });
            static::addGlobalScope('statusPublished', function (Builder $builder) {
                $builder->where('status', 'PUBLISHED');
            });
        }
    }
    
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    /*
     * Relationships
     */
    public function event()
    {
        return $this->belongsTo('App\Event');
    }
    public function data()
    {
       return $this->hasMany('App\EventTimetableData');
    }

    /**
     * Get Available Time slots for timetable
     * @param  boolean $obj [Return as Object]
     * @return Array
     */
    public function getAvailableTimes($obj = false)
    {
        $return = array();
        $end_date = new \DateTime($this->event->end);
        $this_date = new \DateTime($this->event->start);
        while($this_date <= $end_date){
            $return[$this_date->format('Y-m-d H:i:s')] = date("D", strtotime($this_date->format('Y-m-d H:i:s'))) . ' - ' .  date("H:i", strtotime($this_date->format('Y-m-d H:i:s')));
            $this_date->modify('+30 minutes');
        }
        if ($obj) {
            return json_decode(json_encode($return), FALSE);
        }
        return $return;
    }

}