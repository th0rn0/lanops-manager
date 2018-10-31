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
	 * @param $event_id
	 */
	public function setActiveEventParticipant($event_id)
	{
		$clauses = ['user_id' => $this->id, 'signed_in' => true];
		$this->active_event_participant = EventParticipant::where($clauses)->orderBy('updated_at', 'DESC')->first();
	}

	/**
	 * Get Free Tickets for current User
	 * @param  $event_id
	 * @return EventParticipants
	 */
	public function getFreeTickets($event_id)
	{
		$clauses = ['user_id' => $this->id, 'free' => true, 'event_id' => $event_id];
		return EventParticipant::where($clauses)->get();
	}

	/**
	 * Get Staff Tickets for current User
	 * @param  $event_id
	 * @return EventParticipants
	 */
	public function getStaffTickets($event_id)
	{
		$clauses = ['user_id' => $this->id, 'staff' => true, 'event_id' => $event_id];
		return EventParticipant::where($clauses)->get();
	}

	/**
	 * Get Tickets for current User
	 * @param  $event_id
	 * @param  boolean $obj
	 * @return Array|Object 
	 */
	public function getTickets($event_id, $obj = false)
	{
		$clauses = ['user_id' => $this->id, 'event_id' => $event_id];
		$event_participants = EventParticipant::where($clauses)->get();
		$return = array();
		foreach ($event_participants as $event_participant) {
			if (
				($event_participant->ticket && $event_participant->ticket->seatable) ||
				($event_participant->free || $event_participant->staff)
			) {
				$seat = 'Not Seated';
				if ($event_participant->seat) {
					$seat = strtoupper($event_participant->seat->seat);
				}
				$return[$event_participant->id] = 'Participant ID: ' . $event_participant->id . $seat;
				if (!$event_participant->ticket && $event_participant->staff) {
					$return[$event_participant->id] = 'Staff Ticket - Seat: ' . $seat;
				}
				if (!$event_participant->ticket && $event_participant->free) {
					$return[$event_participant->id] = 'Free Ticket - Seat: ' . $seat;
				} 
				if ($event_participant->ticket) {
					$return[$event_participant->id] = $event_participant->ticket->name . ' - Seat: ' . $seat; 
				}
			}
		}
		if ($obj) {
			return json_decode(json_encode($return), FALSE);
		}
		return $return;
	}
}
