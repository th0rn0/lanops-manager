<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventSeating extends Model
{
    /**
     * The name of the table.
     *
     * @var string
     */
    protected $table = 'event_seating';
    
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
        self::updated(function ($model) {

        });
    }

    /*
     * Relationships
     */
    public function seatingPlan()
    {
        return $this->belongsTo('App\Models\EventSeatingPlan', 'event_seating_plan_id');
    }
    public function eventParticipant()
    {
        return $this->belongsTo('App\Models\EventParticipant');
    }
}
