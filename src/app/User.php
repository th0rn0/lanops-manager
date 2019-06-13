<?php

namespace App;

use DB;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'firstname',
        'surname',
        'username_nice',
        'steamname',
        'username',
        'avatar',
        'steamid'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public static function boot()
    {
        parent::boot();
    }
    
    /*
     * Relationships
     */
    public function eventParticipants()
    {
        return $this->hasMany('App\EventParticipant');
    }
    public function purchases()
    {
        return $this->hasMany('App\Purchase');
    }
    public function creditLogs()
    {
        return $this->hasMany('App\CreditLog');
    }

    /**
     * Check if Admin
     * @return Boolean
     */
    public function getAdmin()
    {
        return $this->admin;
    }

    // TODO - Refactor this somehow. It's a bit hacky. - Possible mutators and accessors?
    /**
     * Set Active Event Participant for current User
     * @param $eventId
     */
    public function setActiveEventParticipant($eventId)
    {
        $clauses = ['user_id' => $this->id, 'signed_in' => true];
        $this->active_event_participant = EventParticipant::where($clauses)->orderBy('updated_at', 'DESC')->first();
    }

    /**
     * Get Free Tickets for current User
     * @param  $eventId
     * @return EventParticipants
     */
    public function getFreeTickets($eventId)
    {
        $clauses = ['user_id' => $this->id, 'free' => true, 'event_id' => $eventId];
        return EventParticipant::where($clauses)->get();
    }

    /**
     * Get Staff Tickets for current User
     * @param  $eventId
     * @return EventParticipants
     */
    public function getStaffTickets($eventId)
    {
        $clauses = ['user_id' => $this->id, 'staff' => true, 'event_id' => $eventId];
        return EventParticipant::where($clauses)->get();
    }

    /**
     * Get Tickets for current User
     * @param  $eventId
     * @param  boolean $obj
     * @return Array|Object
     */
    public function getTickets($eventId, $obj = false)
    {
        $clauses = ['user_id' => $this->id, 'event_id' => $eventId];
        $eventParticipants = EventParticipant::where($clauses)->get();
        $return = array();
        foreach ($eventParticipants as $eventParticipant) {
            if (($eventParticipant->ticket && $eventParticipant->ticket->seatable) ||
                ($eventParticipant->free || $eventParticipant->staff)
            ) {
                $seat = 'Not Seated';
                if ($eventParticipant->seat) {
                    $seat = strtoupper($eventParticipant->seat->seat);
                }
                $return[$eventParticipant->id] = 'Participant ID: ' . $eventParticipant->id . $seat;
                if (!$eventParticipant->ticket && $eventParticipant->staff) {
                    $return[$eventParticipant->id] = 'Staff Ticket - Seat: ' . $seat;
                }
                if (!$eventParticipant->ticket && $eventParticipant->free) {
                    $return[$eventParticipant->id] = 'Free Ticket - Seat: ' . $seat;
                }
                if ($eventParticipant->ticket) {
                    $return[$eventParticipant->id] = $eventParticipant->ticket->name . ' - Seat: ' . $seat;
                }
            }
        }
        if ($obj) {
            return json_decode(json_encode($return), false);
        }
        return $return;
    }

    /**
     * Add Credit for current User
     * @param  $amount
     * @param  Boolean $manual
     * @return Boolean
     */
    public function addCredit($amount, $manual = false)
    {
        $this->credit_total += $this->credit;
        if (!$this->save()) {
            return false;
        }
        return true;
    }
}
