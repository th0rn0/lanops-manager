<?php

namespace App;

use QrCode;

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
		'updated_at'
	);

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

	public function setSignIn($bool = true)
	{
		$this->signed_in = true;
		if(!$bool){
			$this->signed_in = false;
		}
		$this->save();
	}
	public function getGiftSendeeName()
	{
		$clauses = ['id' => $this->gift_sendee];
		$user = User::where($clauses)->first();
		return $user->username;
	}
	public function getAssignedByUser()
	{
		return User::where(['id' => $this->staff_free_assigned_by])->first();
	}
	public function generateQRCode()
	{
		QrCode::format('png');
		QrCode::size(300);
		$ticket_url = 'https://' . config('app.url') . '/tickets/retrieve/' . $this->id;
		$qr_code_path = 'storage/public/images/events/' . $this->event->slug . '/qr/';
		$qr_code_file =  $this->event->slug . '-' . str_random(32) . '.png';
		//Check if directory exists - if not create it
		if (!file_exists($qr_code_path)) {
			mkdir($qr_code_path, 0775, true);
		}
		//Generate QR Code
		QrCode::generate($ticket_url, $qr_code_path . $qr_code_file);
		$this->qrcode = $qr_code_path . $qr_code_file;
	}
}

