<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventVenueImage extends Model
{

    /**
     * The name of the table.
     *
     * @var string
     */
    protected $table = 'event_venue_images';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'path',
        'description',
        'venue_id',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [

    ];
    
    /*
     * Relationships
     */
    public function venue()
    {
        return $this->belongsTo('App\Models\EventVenue');
    }
}
