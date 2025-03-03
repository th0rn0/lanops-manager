<?php

namespace App\Models;

use Auth;
use QrCode;
use Spatie\WebhookServer\WebhookCall;

use Illuminate\Database\Eloquent\Model;

class EventParticipant extends Model
{
    /**
     * The name of the table.
     *
     * @var string
     */
    protected $table = 'event_participants';

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = array(
        'created_at',
        'updated_at',
    );

    protected $fillable = [
        'user_id',
        'event_id',
        'ticket_id',
        'purchase_id',
    ];

    public static function boot()
    {
        parent::boot();
        self::created(function ($model) {
            if (!$model->generateQRCode()) {
                return false;
            }
            // Send Webhook to Discord Bot
            if (config('app.discord_bot_url') != '' && $model->event->discord_link_enabled) {
                WebhookCall::create()
                    ->url(config('app.discord_bot_url') . '/participants/new')
                    ->payload([
                        'username' => ($model->user->steamname != null ? $model->user->steamname : $model->user->username),
                        'discord_id' => $model->user->discord_id,
                        'channel_id' => $model->event->discord_channel_id,
                        'role_id' => $model->event->discord_role_id
                    ])
                    ->useSecret(config('app.discord_bot_secret'))
                    ->dispatch();
            };
            return true;
        });
        self::updated(function ($model) {
            if (
                config('app.discord_bot_url') != '' &&
                array_key_exists('user_id', $model->getDirty()) &&
                array_key_exists('gift_accepted', $model->getDirty()) &&
                $model->getDirty()['gift_accepted']
            ) {
                $newUser = User::find($model->getOriginal('user_id'));
                WebhookCall::create()
                    ->url(config('app.discord_bot_url') . '/participants/gifted')
                    ->payload([
                        'gifted_by' => ($newUser->steamname != null ? $newUser->steamname : $newUser->username),
                        'username' => ($model->user->steamname != null ? $model->user->steamname : $model->user->username),
                        'discord_id' => $model->user->discord_id,
                        'channel_id' => $model->event->discord_channel_id,
                        'role_id' => $model->event->discord_role_id
                    ])
                    ->useSecret(config('app.discord_bot_secret'))
                    ->dispatch();
            };
            if (
                config('app.discord_bot_url') != '' &&
                array_key_exists('user_id', $model->getDirty()) &&
                array_key_exists('transferred', $model->getDirty()) &&
                $model->getDirty()['transferred']
            ) {
                WebhookCall::create()
                ->url(config('app.discord_bot_url') . '/participants/gifted')
                ->payload([
                    'username' => ($model->user->steamname != null ? $model->user->steamname : $model->user->username),
                    'discord_id' => $model->user->discord_id,
                    'channel_id' => $model->event->discord_channel_id,
                    'role_id' => $model->event->discord_role_id
                ])
                ->useSecret(config('app.discord_bot_secret'))
                ->dispatch();
            };
            return true;
        });
    }

    /*
     * Relationships
     */
    public function event()
    {
        return $this->belongsTo('App\Models\Event');
    }
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
    public function ticket()
    {
        return $this->belongsTo('App\Models\EventTicket', 'ticket_id');
    }
    public function purchase()
    {
        return $this->belongsTo('App\Models\Purchase', 'purchase_id');
    }
    public function seat()
    {
        return $this->hasOne('App\Models\EventSeating');
    }

    /**
     * Set Event Participant as Signed in
     * @param Boolean $bool
     */
    public function setSignIn($bool = true)
    {
        $this->signed_in = true;
        if (!$bool) {
            $this->signed_in = false;
        }
        if (!$this->save()) {
            return false;
        }
        return true;
    }

    /**
     * Get User that Assigned Ticket
     * @return User
     */
    public function getAssignedByUser()
    {
        return User::where(['id' => $this->staff_free_assigned_by])->first();
    }

    /**
     * Get User that Assigned Ticket
     * @return User
     */
    public function getGiftedByUser()
    {
        return User::where(['id' => $this->gift_sendee])->first();
    }

    /**
     * Regenerate QR Codes
     * @return boolean
     */
    public function generateQRCode()
    {
        $ticketUrl = 'https://' . config('app.url') . '/tickets/retrieve/' . $this->id;
        $qrCodePath = 'storage/images/events/' . $this->event->slug . '/qr/';
        $qrCodeFileName = $this->event->slug . '-' . str_random(32) . '.png';
        if (!file_exists($qrCodePath)) {
            mkdir($qrCodePath, 0775, true);
        }
        QrCode::format('png');
        QrCode::size(300);
        QrCode::generate($ticketUrl, $qrCodePath . $qrCodeFileName);
        $this->qrcode = $qrCodePath . $qrCodeFileName;
        if (!$this->save()) {
            return false;
        }
        return true;
    }

    /**
     * Transfer Participant to another Event
     * @param $type
     * @return boolean
     */
    public function transfer($eventId)
    {
        $this->transferred = true;
        $this->transferred_event_id = $this->event_id;
        $this->event_id = $eventId;
        if (!$this->save()) {
            return false;
        }
        return true;
    }

    /**
     * Get New Participants
     * @param $type
     * @return EventParticipant
     */
    public static function getNewParticipants($type = 'all')
    {
        if (!$user = Auth::user()) {
            $type = 'all';
        }
        switch ($type) {
            case 'login':
                $particpants = self::where('created_at', '>=', $user->last_login)->get();
                break;
            default:
                $particpants = self::where('created_at', '>=', date('now - 1 day'))->get();
                break;
        }
        return $particpants;
    }
}
