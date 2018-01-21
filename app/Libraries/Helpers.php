<?php

namespace App\Libraries;

class Helpers
{
	public static function getVenues()
	{
		$venues = \App\EventVenue::all();
		$return_array = array();
		foreach($venues as $venue){
				$return_array[$venue->id] = $venue->display_name;
		}
		return $return_array;
	}

	public static function getEvents($order = 'DESC', $limit = 0, $array = false)
	{
		if ($limit != 0) {
			$events = \App\Event::orderBy('start', $order)->paginate($limit);
		} else {
			$events = \App\Event::orderBy('start', 'DESC')->get();
		}
		if ($array) {
			$events_array[0] = 'None';
			foreach($events as $event){
				$events_array[$event->id] = $event->display_name;
			}
			$events = $events_array;
		}

		return $events;
	}

	public static function getEventTotal()
	{
		$events = \App\Event::count();
		return Settings::getLanCountOffset() + $events;
	}

	public static function getNextEventname()
	{
		if ($event = \App\Event::where('end', '>=', \Carbon\Carbon::now())->first()) {
			if ($event->status == 'DRAFT' || $event->status == 'PREVIEW') {
				return $event->display_name . ' - ' . $event->status;
			}
			return $event->display_name;
		}
		return 'No new Events';
	}

	public static function getNextEventSlug()
	{
		if ($event = \App\Event::where('end', '>=', \Carbon\Carbon::now())->first()) {
			return $event->slug;
		}
		return '#';
	}

	public static function getEventParticipantTotal()
	{
		$participants = \App\EventParticipant::count();
		//DEBUG - Put the offset as config variable
		return 686 + $participants;
	}

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

	//DEBUG - Do array as default and object as param on ALL
	public static function getBasketFormat($basket, $obj = false)
	{
		$return = array();
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