<?php

namespace App;

use DB;
use Auth;
use Settings;

use App\CreditLog;

use \Carbon\Carbon as Carbon;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

// TODO - REMOVE MUST VERIFY EMAIL
class User extends Authenticatable implements MustVerifyEmail
{

    use Notifiable;

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
        'steamid',
        'last_login',
        'email_verified_at'
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
     * Check Credit amount for current user
     * @param  $amount
     * @return Boolean
     */
    public function checkCredit($amount)
    {
        if (($this->credit_total + $amount) < 0) {
            return false;
        }
        return true;
    }

    /**
     * Edit Credit for current User
     * @param  $amount
     * @param  Boolean $manual
     * @param  $reason
     * @param  Boolean $buy
     * @param  $purchaseId
     * @return Boolean
     */
    public function editCredit($amount, $manual = false, $reason = 'System Automated', $buy = false, $purchaseId = null)
    {
        $this->credit_total += $amount;
        $admin_id = null;
        if ($manual) {
            $admin_id = Auth::id();
            $reason = 'Manual Edit';
        }
        $action = 'ADD';
        if ($amount < 0) {
            $action = 'SUB';
        }
        if ($buy) {
            $action = 'BUY';
        }
        if ($amount != 0) {
            CreditLog::create([
                'user_id'       => $this->id,
                'action'        => $action,
                'amount'        => $amount,
                'reason'        => $reason,
                'purchase_id'   => $purchaseId,
                'admin_id'      => $admin_id
            ]);
        }
        if (!$this->save()) {
            return false;
        }
        return true;
    }

    /**
     * Get Orders for Current User
     * @return ShopOrder
     */
    public function getOrders()
    {
        $return = collect();
        foreach ($this->purchases as $purchase) {
            if ($purchase->order) {
                $return->prepend($purchase->order);
            }
        }
        return $return;
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new \App\Notifications\ResetPassword($token));
    }

    /**
     * Get Next Event for Current User
     * @return Event
     */
    public function getNextEvent()
    {
        $nextEvent = false;
        foreach ($this->eventParticipants as $eventParticipant) {
            if ($eventParticipant->event->end >=  Carbon::now()) {
                if (!isset($nextEvent) || !$nextEvent) {
                    $nextEvent = $eventParticipant->event;
                }
                if ($nextEvent->end >= $eventParticipant->event->end) {
                    $nextEvent = $eventParticipant->event;
                }
            } 
        }
        return $nextEvent;
    }

}
