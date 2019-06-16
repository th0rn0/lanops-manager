<?php

namespace App;

use QrCode;
use Settings;

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
            if (Settings::isCreditEnabled()) {
                if (Settings::getCreditRegistrationSite() != 0 || Settings::getCreditRegistrationSite() != null) {
                    $model->user->editCredit(Settings::getCreditRegistrationEvent(), false, 'Event Registration');
                }
            }
            return true;
        });
    }

    /*
     * Relationships
     */
    public function event()
    {
        return $this->belongsTo('App\Event');
    }
    public function user()
    {
        return $this->belongsTo('App\User');
    }
    public function ticket()
    {
        return $this->belongsTo('App\EventTicket', 'ticket_id');
    }
    public function purchase()
    {
        return $this->belongsTo('App\Purchase', 'purchase_id');
    }
    public function tournamentParticipants()
    {
        return $this->hasMany('App\EventTournamentParticipant');
    }
    public function seat()
    {
        return $this->hasOne('App\EventSeating');
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
     * @return Boolean
     */
    public function generateQRCode()
    {
        $ticketUrl = 'https://' . config('app.url') . '/tickets/retrieve/' . $this->id;
        $qrCodePath = 'storage/images/events/' . $this->event->slug . '/qr/';
        $qrCodeFileName =  $this->event->slug . '-' . str_random(32) . '.png';
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
}
