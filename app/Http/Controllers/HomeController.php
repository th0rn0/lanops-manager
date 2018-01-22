<?php

namespace App\Http\Controllers;

use DB;
use Auth;

use App\Event;
use App\EventTimetable;
use App\EventTimetableData;
use App\EventParticipant;
use App\EventParticipantType;

use App\Http\Requests;

use Illuminate\Http\Request;

class HomeController extends Controller
{
	/**
	 * Show Index Page
	 * @return Function
	 */
	public function index()
	{
		$user = Auth::user();
		if ($user && !empty($user->eventParticipants)) {
			foreach ($user->eventParticipants as $participant) {
				if ((date('Y-m-d H:i:s') >= $participant->event->start) && (date('Y-m-d H:i:s') <= $participant->event->end) && $participant->signed_in) {
					return $this->lan(); 
				}
			}
		}
		return $this->net();
	}

	/**
	 * Show New Page
	 * @return View
	 */
	public function net()
	{
		$events = Event::where('start', '>=', date("Y-m-d 00:00:00"))
							->orderBy('id', 'desc')
							->limit(1)
							->get();
		$news = DB::select('select news_feed.*, users.username from news_feed left join users on news_feed.user_id = users.id limit 3');
		return view("home")->withEvents($events)->withNews($news);
	}
	
	/**
	 * Show About us Page
	 * @return View
	 */
	public function about()
	{
		return view("about");
	}
	
	/**
	 * Show Contact Page
	 * @return View
	 */
	public function contact()
	{
		return view("contact");
	}
	
	/**
	 * Show Lan Page
	 * @return View
	 */
	public function lan()
	{
		$signed_in = true;
		$event = Event::where('start', '<', date("Y-m-d H:i:s"))->orderBy('id', 'desc')->first();
		$event->load('eventParticipants.user');
		$event->load('timetables');
		foreach ($event->timetables as $timetable) {
			$timetable->data = EventTimetableData::where('event_timetable_id', $timetable->id)->orderBy('start_time', 'asc')->get();
		}

		foreach ($event->tournaments as $tournament) {
			if ($tournament->status == 'COMPLETE') {
				$tournament->challonge_participants = $tournament->getChallongeParticipants();
			}
		}

		$user = Auth::user();
		if ($user) {
			$clauses = ['user_id' => $user->id, 'event_id' => $event->id]; 
			$user->event_participation = EventParticipant::where($clauses)->get();
		}

		$ticketFlag = false;
		if ($user) {
			$user->setActiveEventParticipant($event->id);
			if ($user->eventParticipation != null || isset($user->eventParticipation)) {
				foreach ($user->eventParticipation as $participant) {
					if ($participant->event_id == $event->id) {
						$ticketFlag = true;
					} 
				}
			}
		}
		return view("lan.home")->withEvent($event)->withTicketFlag($ticketFlag)->withSignedIn($signed_in)->withUser($user);
	}

	/**
	 * Show Big Screen Page
	 * @param  Event  $event
	 * @return View
	 */
	public function bigScreen(Event $event)
	{
		return view("lan.big")->withEvent($event);

	}
}
