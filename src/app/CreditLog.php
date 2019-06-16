<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CreditLog extends Model
{
    /**
     * The name of the table.
     *
     * @var string
     */
    protected $table = 'credit_log';

    protected $fillable = ['user_id', 'action', 'amount', 'reason', 'event_ticket_id', 'admin_id'];

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
    public function admin()
    {
        return $this->belongsTo('App\User', 'admin_id');
    }
    
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
    
    public function tickets()
    {
        return $this->belongsTo('App\EventTicket', 'event_ticket_id');
    }
}
