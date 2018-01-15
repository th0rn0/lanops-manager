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

    /**
     * The name of the table.
     *
     * @var string
     */
    protected $table = 'event_timetables';

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
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
    
    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'display_name'
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

    function getSlotsArray()
    {
        $end_date = new \DateTime($this->event->end);
        $this_date = new \DateTime($this->event->start);
        while($this_date <= $end_date){
            $return_array[$this_date->format('Y-m-d H:i:s')] = date("D", strtotime($this_date->format('Y-m-d H:i:s'))) . ' - ' .  date("H:i", strtotime($this_date->format('Y-m-d H:i:s')));
            $this_date->modify('+30 minutes');
        }
        return $return_array;
    }

}