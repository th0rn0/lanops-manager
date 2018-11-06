<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EventSponsor extends Model
{
    /**
     * The name of the table.
     *
     * @var string
     */
    protected $table = 'event_sponsors';

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