<?php

namespace App;

use App\EventTimetable;
use App\Http\Requests;
use Illuminate\Database\Eloquent\Model;

class EventTimetableData extends Model
{
    protected $table = 'event_timetable_data';

    protected $hidden = array(
        'created_at',
        'updated_at'
    );
    
    /*
     * Relationships
     */
    public function timetable()
    {
        return $this->belongsTo('App\EventTimetable');
    }
}