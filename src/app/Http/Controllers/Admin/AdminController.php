<?php

namespace App\Http\Controllers\Admin;

use Auth;
use Helpers;
use \Carbon\Carbon as Carbon;

use App\User;
use App\Event;
use App\Poll;
use App\PollOptionVote;
use App\EventParticipant;
use App\NewsComment;
use App\EventTicket;

use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    /**
     * Show Admin Index Page
     * @return view
     */
    public function index()
    {
        $user = Auth::user();
        $users = User::all();
        $events = Event::all();
        $participants = EventParticipant::getNewParticipants('login');
        $participantCount = EventParticipant::all()->count();
        $votes = PollOptionVote::getNewVotes('login');
        $comments = NewsComment::getNewComments('login');
        $tickets = EventTicket::all();
        $activePolls = Poll::where('end', '==', null)->orWhereBetween('end', ['0000-00-00 00:00:00', date("Y-m-d H:i:s")]);
        $userLastLoggedIn = User::where('id', '!=', Auth::id())->latest('last_login')->first();
        $ticketBreakdown = array();
        foreach (EventParticipant::where('created_at', '>=', Carbon::now()->subMonths(12)->month)->get() as $participant) {
            $ticketBreakdown[date_format($participant->created_at, 'm')][] = $participant;
        }
        return view('admin.index')
            ->withUser($user)
            ->withEvents($events)
            ->withParticipants($participants)
            ->withVotes($votes)
            ->withComments($comments)
            ->withTickets($tickets)
            ->withActivePolls($activePolls)
            ->withUserLastLoggedIn($userLastLoggedIn)
            ->withUserCount($users->count())
            ->withParticipantCount($participantCount)
            ->withNextEvent(Helpers::getNextEventName())
            ->withTicketBreakdown($ticketBreakdown);
    }
}
