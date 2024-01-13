<?php

namespace App\Http\Controllers\Admin;

use DB;
use Auth;
use Settings;
use Helpers;
use FacebookPageWrapper as Facebook;
use \Carbon\Carbon as Carbon;

use App\User;
use App\Event;
use App\Poll;
use App\PollOptionVote;
use App\EventParticipant;
use App\NewsComment;
use App\EventTicket;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

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
        $facebookCallback = null;
        if (Facebook::isEnabled() && !Facebook::isLinked()) {
            $facebookCallback = Facebook::getLoginUrl();
        }
        $userLastLoggedIn = User::where('id', '!=', Auth::id())->latest('last_login')->first();
        $loginSupportedGateways = Settings::getSupportedLoginMethods();
        foreach ($loginSupportedGateways as $gateway) {
            $count = 0;
            switch ($gateway) {
                case 'steam':
                    $count = $users->where('steamid', '!=', null)->count();
                    break;
                default:
                    $count = $users->where('password', '!=', null)->count();
                    break;
            }
            $userLoginMethodCount[$gateway] = $count;
        }
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
            ->withSupportedLoginMethods(Settings::getSupportedLoginMethods())
            ->withActiveLoginMethods(Settings::getLoginMethods())
            ->withSupportedPaymentGateways($loginSupportedGateways)
            ->withActivePaymentGateways(Settings::getPaymentGateways())
            ->withFacebookCallback($facebookCallback)
            ->withUserLastLoggedIn($userLastLoggedIn)
            ->withUserCount($users->count())
            ->withUserLoginMethodCount($userLoginMethodCount)
            ->withParticipantCount($participantCount)
            ->withNextEvent(Helpers::getNextEventName())
            ->withTicketBreakdown($ticketBreakdown);
    }
}
