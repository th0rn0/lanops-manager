<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\EventTimetable;
use App\Http\Requests;

class EventTimetableData extends Model
{
    /**
     * The name of the table.
     *
     * @var string
     */
    protected $table = 'event_timetable_data';

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
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
    
    public function remove()
    {
        //Null all data related to the timetable Slot
        $this->slot = NULL;
        $this->desc = NULL;
        try {
           $this->save();
        } catch (ModelNotFoundException $ex) {
            // Error handling code
            return FALSE;
        }
        return TRUE;

    }
    public function store(EventTimetable $timetable, $request)
    {
        $this->event_timetable_id = $timetable->id;
        $this->slot_timestamp = date( 'Y-m-d H:i:s', strtotime($request->start));
        $this->slot = $request->game;
        $this->desc = $request->desc;
        try {
          $this->save();
        } catch (ModelNotFoundException $ex) {
          // Error handling code
          return FALSE;
        }
        return TRUE;
    }
}
