<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use Settings;

use App\Http\Requests;

use Illuminate\Http\Request;

class AccountController extends Controller
{
    /**
     * Show Account Index Page
     * @return View
     */
    public function index()
    {
        $user = Auth::user();
        $creditLogs = false;
        if (Settings::isCreditEnabled()) {
            $creditLogs = $user->creditLogs()->paginate(5, ['*'], 'credit_logs');
        }
        $purchases = $user->purchases()->paginate(5, ['*'], 'purchases');
        $tickets = $user->eventParticipants()->paginate(5, ['*'], 'tickets');
        return view("accounts.index")
            ->withUser($user)
            ->withCreditLogs($creditLogs)
            ->withPurchases($purchases)
            ->withEventParticipants($tickets)
        ;
    }
}
