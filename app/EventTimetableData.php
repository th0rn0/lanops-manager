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
    
    /**
     * Delete all data associated with this slot
     * @return Boolean
     */
    public function remove()
    {
        $this->name = NULL;
        $this->desc = NULL;
        try {
           $this->save();
        } catch (ModelNotFoundException $ex) {
            // Error handling code
            return FALSE;
        }
        return TRUE;

    }

    /**
     * Store new data for this slot
     * @param  EventTimetable $timetable
     * @param  Request        $request
     * @return Boolean
     */
    public function store(EventTimetable $timetable, $request)
    {
        $this->event_timetable_id   = $timetable->id;
        $this->start_time           = date( 'Y-m-d H:i:s', strtotime($request->start));
        $this->name                 = $request->game;
        $this->desc                 = $request->desc;
        try {
          $this->save();
        } catch (ModelNotFoundException $ex) {
          // Error handling code
          return FALSE;
        }
        return TRUE;
    }
}
