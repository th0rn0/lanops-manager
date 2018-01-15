<?php

namespace App;

use DB;

use Illuminate\Database\Eloquent\Model;

class EventInformation extends Model
{
    /**
     * The name of the table.
     *
     * @var string
     */
    protected $table = 'event_information';
    
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
    public function event()
    {
        return $this->belongsTo('App\Event');
    }
}
