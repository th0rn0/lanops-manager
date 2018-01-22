<?php

namespace App\Libraries;

class Helpers
{
	/**
	 * Get Venues
	 * @param  boolean $obj Return as Object
	 * @return Array|Object
	 */
	public static function getVenues($obj = false)
	{
		$venues = \App\EventVenue::all();
		$return = array();
		foreach($venues as $venue){
				$return[$venue->id] = $venue->display_name;
		}
		if (!$obj) {
			$return[] = 'None';
		}
		if ($obj) {
			return json_decode(json_encode($return), FALSE);
		}
		return $return;
	}

	/**
	 * Get Events
	 * @param  string  $order
	 * @param  integer $limit
	 * @param  boolean $obj   Return as Object
	 * @return Array|Object
	 */
	public static function getEvents($order = 'DESC', $limit = 0, $obj = false)
	{
		if ($limit != 0) {
			$events = \App\Event::orderBy('start', $order)->paginate($limit);
		} else {
			$events = \App\Event::orderBy('start', 'DESC')->get();
		}
		foreach ($events as $event) {
			$return[$event->id] = $event;
		}
		if ($obj) {
			return json_decode(json_encode($return), FALSE);
		}
		return $return;
	}

	/**
	 * Get Event Names
	 * @param  string  $order
	 * @param  integer $limit
	 * @param  boolean $obj   Return as Object
	 * @return Array|Object
	 */
	public static function getEventNames($order = 'DESC', $limit = 0, $obj = false)
	{
		if ($limit != 0) {
			$events = \App\Event::orderBy('start', $order)->paginate($limit);
		} else {
			$events = \App\Event::orderBy('start', 'DESC')->get();
		}
		if (!$obj) {
			$return[] = 'None';
		}
		foreach ($events as $event) {
			$return[$event->id] = $event->display_name;
		}
		if ($obj) {
			return json_decode(json_encode($return), FALSE);
		}
		return $return;
	}

	/**
	 * Get Total Events Count
	 * @return Integer
	 */
	public static function getEventTotal()
	{
		$events = \App\Event::count();
		return Settings::getLanCountOffset() + $events;
	}

	/**
	 * Get Next Event Name
	 * @return String
	 */
	public static function getNextEventName()
	{
		if ($event = \App\Event::where('end', '>=', \Carbon\Carbon::now())->first()) {
			if ($event->status == 'DRAFT' || $event->status == 'PREVIEW') {
				return $event->display_name . ' - ' . $event->status;
			}
			return $event->display_name;
		}
		return 'No new Events';
	}

	/**
	 * Get Next Event Slug
	 * @return String
	 */
	public static function getNextEventSlug()
	{
		if ($event = \App\Event::where('end', '>=', \Carbon\Carbon::now())->first()) {
			return $event->slug;
		}
		return '#';
	}

	/**
	 * Get Total Event Participants Count
	 * @return Integer
	 */
	public static function getEventParticipantTotal()
	{
		$participants = \App\EventParticipant::count();
		return Settings::getParticipantCountOffset() + $participants;
	}

	/**
	 * Get Active Tournaments count for User
	 * @param  $event_id
	 * @return Integer
	 */
	public static function getUserActiveTournaments($event_id)
	{
		$user = \Auth::user();
		$active_tournament_counter = 0;
		foreach ($user->eventParticipants as $event_participant){
			foreach ( $event_participant->tournamentParticipants as $tournament_participant){
				if ($tournament_participant->eventTournament->event_id == $event_id && $tournament_participant->eventTournament->status != 'COMPLETE'){
					$active_tournament_counter++;
				}
			}
		}
		return $active_tournament_counter;
	}

	/**
	 * Format Challonge Rankings
	 * @param  $final_rank
	 * @return String
	 */
	public static function getChallongeRankFormat($final_rank)
	{
		if($final_rank == '1'){
			return 'Winner';
		}
		if($final_rank == '2'){
			return '1st Runner up';
		}
		if($final_rank == '3'){
			return '2nd Runner up';
		}
		if(substr($final_rank, -1) == '1'){
			return $final_rank . 'st';
		}
		if(substr($final_rank, -1) == '2'){
			return $final_rank . 'nd';
		}
		if(substr($final_rank, -1) == '3'){
			return $final_rank . 'rd';
		}
		return $final_rank . 'th';
	}

	/**
	 * Format Basket
	 * @param  $basket
	 * @param  boolean $obj    Return as Object
	 * @return Array|Object
	 */
	public static function getBasketFormat($basket, $obj = false)
	{
		$return = array();
		if (!$obj) {
			$return[] = 'None';
		}
		foreach ($basket as $ticket_id => $quantity) {
			$ticket = \App\EventTicket::where('id', $ticket_id)->first();
			array_push(
				$return,
				[
					'id'        => $ticket_id,
					'name'      => $ticket->name,
					'price'     => $ticket->price, 
					'quantity'  => $quantity
				]
			);
		}
		if ($obj) {
			return json_decode(json_encode($return), FALSE);
		}
		return $return;
	}

	/**
	 * Get Basket Total
	 * @param  $basket
	 * @return Integer
	 */
	public static function getBasketTotal($basket)
	{
		$return = 0;
		foreach ($basket as $ticket_id => $quantity) {
			$ticket = \App\EventTicket::where('id', $ticket_id)->first();
			$return += ($ticket->price * $quantity);
		}
		return $return;
	}
}