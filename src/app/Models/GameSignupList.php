<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GameSignupList extends Model
{
    /**
     * The name of the table.
     *
     * @var string
     */
    protected $table = 'game_signup_list';

    protected $fillable = [
        'name',
        'event_id',
        'slug',
        'team_size',
        'status'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = array(
        'created_at',
    );

    /*
     * Relationships
     */
    public function event()
    {
        return $this->belongsTo('App\Models\Event', 'event_id');
    }
}
