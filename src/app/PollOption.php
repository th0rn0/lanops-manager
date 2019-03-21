<?php

namespace App;

use DB;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class PollOption extends Model
{

    /**
     * The name of the table.
     *
     * @var string
     */
    protected $table = 'polls';
    
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
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function poll()
    {
        return $this->belongsTo('App\Poll', 'poll_id');
    }
}
