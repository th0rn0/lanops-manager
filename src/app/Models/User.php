<?php

namespace App\Models;

use \Carbon\Carbon as Carbon;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Spatie\WebhookServer\WebhookCall;

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
        self::updated(function ($model) {
            if (
                config('app.discord_bot_url') != '' &&
                array_key_exists('discord_id', $model->getDirty())
            ) {
                if ($model->discord_id != null && $model->getUpcomingEvents()) {
                    foreach($model->getUpcomingEvents() as $event) {
                        if ($event->discord_link_enabled) {
                            WebhookCall::create()
                            ->url(config('app.discord_bot_url') . '/participants/new')
                            ->payload([
                                'username' => $model->steamname,
                                'discord_id' => $model->discord_id,
                                'channel_id' => $event->discord_channel_id,
                                'role_id' => $event->discord_role_id,
                                'no_message' => true
                            ])
                            ->useSecret(config('app.discord_bot_secret'))
                            ->dispatch();
                        }
                    }
                }

                if ($model->discord_id == null && $model->getUpcomingEvents()) {
                    foreach($model->getUpcomingEvents() as $event) {
                        if ($event->discord_link_enabled) {
                            WebhookCall::create()
                            ->url(config('app.discord_bot_url') . '/participants/remove')
                            ->payload([
                                'username' => $model->steamname,
                                'discord_id' => $model->getOriginal('discord_id'),
                                'channel_id' => $event->discord_channel_id,
                                'role_id' => $event->discord_role_id,
                            ])
                            ->useSecret(config('app.discord_bot_secret'))
                            ->dispatch();
                        }
                    }
                }
            };
        });

    }
    
    /*
     * Relationships
     */
    public function eventParticipants()
    {
        return $this->hasMany('App\Models\EventParticipant');
    }
    public function purchases()
    {
        return $this->hasMany('App\Models\Purchase');
    }

    public function referralPurchases()
    {
        return $this->hasMany('App\Models\Purchase', 'referral_code_user_id');
    }

    public function gameListParticipants()
    {
        return $this->hasMany('App\Models\GameSignupListParticipant');
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

    public function getUpcomingEvents()
    {
        $nextEvents = false;
        foreach ($this->eventParticipants as $eventParticipant) {
            if ($eventParticipant->event->end >=  Carbon::now()) {
                $nextEvents[] = $eventParticipant->event;
            } 
        }
        if ($nextEvents) {
            $nextEvents = array_unique($nextEvents);
        }
        return $nextEvents;
    }

    public function getFormattedDiscordAvatar()
    {
        if (substr($this->discord_avatar, 0, 2) == "a_") {
            return $this->discord_avatar.".gif";
        }
        return $this->discord_avatar.".png";
    }

    public function isReferrable()
    {
        return count($this->eventParticipants) == 0;
    }

    public static function generateReferralCode()
    {
        return bin2hex(random_bytes(10));
    }

    public static function isValidReferralCode($referralCode, User $excludeUser = null)
    {
        if ($excludeUser) {
            return User::where('referral_code', $referralCode)
                ->where('id', '!=', $excludeUser->id)
                ->withCount('eventParticipants')
                ->having('event_participants_count', '>', 0)
                ->first();
        }
        return User::where('referral_code', $referralCode)
            ->withCount('eventParticipants')
            ->having('event_participants_count', '>', 0)
            ->first();
    }

    public static function getUserByReferralCode($referralCode)
    {
        return User::where('referral_code', $referralCode)->first();
    }

    public function getReferralsRedeemedCount()
    {
        return count($this->referralPurchases()->whereNot('referral_code_discount_redeemed_purchase_id', null)->get());
    }

    public function getReferralsUnclaimedCount()
    {
        return count($this->referralPurchases()->where('referral_code_discount_redeemed_purchase_id', null)->get());
    }

    public function getAvailableReferralPurchase()
    {
        return $this->referralPurchases->where('referral_code_discount_redeemed_purchase_id', null)->first();
    }
}
