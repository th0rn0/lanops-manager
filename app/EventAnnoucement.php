<?php

namespace App;

use DB;

use Illuminate\Database\Eloquent\Model;

class EventAnnoucement extends Model 
{
    /**
     * The name of the table.
     *
     * @var string
     */
    protected $table = 'event_annoucements';
    
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
        return $this->belongsTo('App\Event');
    }

}
