<?php

namespace App;

use DB;
use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Cviebrock\EloquentSluggable\Sluggable;

class Event extends Model 
{
	use Sluggable;

	/**
	 * The name of the table.
	 *
	 * @var string
	 */
	protected $table = 'events';
	
	protected $dates = ['deleted_at'];

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'display_name',
		'status', 
		'start', 
		'end', 
		'description',
		'seating_cap',
		'spectator_cap',
		'ticket_spectator',
		'ticket_weekend'
	];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array(
		'created_at',
		'updated_at'
	);

	protected static function boot()
	{
		parent::boot();

		$admin = false;
		if (Auth::user() && Auth::user()->getAdmin()) {
			$admin = true;
		}
		if(!$admin) {
			static::addGlobalScope('statusDraft', function (Builder $builder) {
				$builder->where('status', '!=', 'DRAFT');
			});
			static::addGlobalScope('statusPublished', function (Builder $builder) {
				$builder->where('status', 'PUBLISHED');
			});
		}
	}

	/*
	 * Relationships
	 */
	public function eventParticipants()
	{
		return $this->hasMany('App\EventParticipant');
	}
	public function timetables()
	{
		return $this->hasMany('App\EventTimetable');
	}
	public function tickets()
	{
		return $this->hasMany('App\EventTicket');
	}
	public function seatingPlans()
	{
		return $this->hasMany('App\EventSeatingPlan');
	}
	public function tournaments()
	{
		return $this->hasMany('App\EventTournament');
	}
	public function sponsors()
	{
		return $this->hasMany('App\EventSponsor');
	}
	public function information()
	{
		return $this->hasMany('App\EventInformation');
	}
	public function annoucements()
	{
		return $this->hasMany('App\EventAnnoucement');
	}
	public function venue()
	{
		return $this->belongsTo('App\EventVenue', 'event_venue_id');
	}
	public function galleries()
	{
		return $this->hasMany('App\GalleryAlbum');
	}

	/**
	 * Return the sluggable configuration array for this model.
	 *
	 * @return array
	 */
	public function sluggable()
	{
		return [
			'slug' => [
				'source' => 'nice_name'
			]
		];
	}
	
	/**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

	public function getSeat($seating_plan_id, $seat)
	{
		$seating_plan = $this->seatingPlans()->find($seating_plan_id);
		return $seating_plan->seats()->where('seat', ucwords($seat))->first();
	}
	
	public function getUser($user_id = null)
	{
		if ($user_id == null) {
			$user_id = Auth::id();
		}
		return $this->eventParticipants()->where('user_id', $user_id)->first();
	}

	//DEBUG - CHANGE ME TO getTicketSalesCount
	public function getTotalTicketSales()
	{
		$total = 0;
		foreach ($this->eventParticipants as $participant) {
			if ($participant->purchase && $participant->ticket) {
				$total = $total + $participant->ticket->price;
			}
		}
		return $total;
	}

	public function getSeatedCount()
	{
		$total = 0;
		foreach ($this->seatingPlans as $seating_plan) {
			$total += $seating_plan->seats()->count();
		}
		return $total;
	}

	public function getSeatingCapacity()
	{
		$total = 0;
		foreach ($this->seatingPlans as $seating_plan) {
			$total += ($seating_plan->columns * $seating_plan->rows);
		}
		return $total;
	}

	public function getParticipantsSelectArray()
	{
		$return = array();
		foreach ($this->eventParticipants as $participant) {
			if (($participant->staff || $participant->free) || $participant->ticket->seatable) {
				$seat = 'NOT SEATED';
				if (!empty($participant->seat)) {
					$seat = $participant->seat->seat;
				}
				$text = $participant->user->username . ' - ' . $seat;
				if ($participant->staff) {
					$text = $participant->user->username . ' - ' . $seat . ' - Staff Ticket';
				}
				if ($participant->free) {
					$text = $participant->user->username . ' - ' . $seat . ' - Free Ticket';
				}
				$return[$participant->id] = $text;
			}
		}
		return $return;
	}
}