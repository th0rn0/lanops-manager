<?php

namespace App\Http\Controllers;

use DB;
use Auth;

use App\Event;
use App\User;
use App\NewsArticle;
use App\EventTimetable;
use App\EventTimetableData;
use App\EventParticipant;
use App\EventTournamentTeam;
use App\EventTournamentParticipant;

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
		$top_attendees = array();
		foreach (EventParticipant::groupBy('user_id', 'event_id')->get() as $attendee) {
			if ($attendee->event->end < \Carbon\Carbon::today()) {
				$recent = false;
				if (!$attendee->user->admin && array_key_exists($attendee->user->id, $top_attendees)) {
					$top_attendees[$attendee->user->id]->event_count++;
					$recent = true;
				}
				if (!$attendee->user->admin && !$recent) {
					$attendee->user->event_count = 1;
					$top_attendees[$attendee->user->id] = $attendee->user;
				}
			}
		}
		usort($top_attendees, function($a, $b) {
		    return $b['event_count'] <=> $a['event_count'];
		});

		$top_winners = array();
		foreach (EventTournamentTeam::where('final_rank', 1)->get() as $winner_team) {
			$recent = false;
			foreach ($winner_team->tournamentParticipants as $winner) {
				if (array_key_exists($winner->eventParticipant->user->id, $top_winners)) {
					$top_winners[$winner->eventParticipant->user->id]->win_count++;
					$recent = true;
				}
				if (!$recent) {
					$winner->eventParticipant->user->win_count = 1;
					$top_winners[$winner->eventParticipant->user->id] = $winner->eventParticipant->user;
				}
			}
		}
		foreach (EventTournamentParticipant::where('final_rank', 1)->get() as $winner) {
			$recent = false;
			if (array_key_exists($winner->eventParticipant->user->id, $top_winners)) {
				$top_winners[$winner->eventParticipant->user->id]->win_count++;
				$recent = true;
			}
			if (!$recent) {
				$winner->eventParticipant->user->win_count = 1;
				$top_winners[$winner->eventParticipant->user->id] = $winner->eventParticipant->user;
			}
		}
		usort($top_winners, function($a, $b) {
		    return $b['win_count'] <=> $a['win_count'];
		});
		return view("home")
			->withNextEvent(Event::where('end', '>=', \Carbon\Carbon::now())->orderBy(DB::raw('ABS(DATEDIFF(events.end, NOW()))'))->first())
			->withTopAttendees(array_slice($top_attendees, 0, 5))
			->withTopWinners(array_slice($top_winners, 0, 5))
			->withNewsArticles(NewsArticle::all())
			->withEvents(Event::all());
		;
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

		// TODO - Refactor
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
