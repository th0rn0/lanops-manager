<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventAnnouncement extends Model
{
    /**
     * The name of the table.
     *
     * @var string
     */
    protected $table = 'event_announcements';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'message',
        'event_id',
    ];

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
        return $this->belongsTo('App\Models\Event');
    }
}
