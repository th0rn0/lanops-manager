<?php

namespace App\Http\Controllers\Admin;

use DB;
use Auth;
use Settings;

use App\User;
use App\CreditLog;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class CreditController extends Controller
{
    /**
     * Show Credit System Index Page
     * @return View
     */
    public function index()
    {
        return view('admin.credit.index')
            ->withIsCreditEnabled(Settings::isCreditEnabled())
            ->withCreditLogs(CreditLog::all())
            ->withCreditAwardTournamentParticipation(Settings::getCreditTournamentParticipation())
            ->withCreditAwardTournamentFirst(Settings::getCreditTournamentFirst())
            ->withCreditAwardTournamentSecond(Settings::getCreditTournamentSecond())
            ->withCreditAwardTournamentThird(Settings::getCreditTournamentThird())
            ->withCreditAwardRegistrationEvent(Settings::getCreditRegistrationEvent())
            ->withCreditAwardRegistrationSite(Settings::getCreditRegistrationSite())
        ;
    }

    /**
     * Manually Edit Credit to User
     * @return View
     */
    public function edit(Request $request)
    {
	 	$rules = [
            'user_id'   => 'required|exists:users,id',
            'amount'    => 'required|integer',
        ];
      	$messages = [
            'user_id.required'	=> 'User ID is Required',
            'user_id.exists'	=> 'User ID must be a valid user.',
            'amount.required' 	=> 'An Amount is Required',
            'amount.integer'  	=> 'Amount must be a number',
        ];
        $this->validate($request, $rules, $messages);

        $user = User::where('id', $request->user_id)->first();
        if (!$user->checkCredit($request->amount)) {
			Session::flash('alert-danger', 'Not enough Credit.');
        	return Redirect::back();
        }
        $action = 'add';
        if ($request->amount < 0) {
        	$action = 'subtract';
        }
        if (!$user->editCredit($request->amount, true)) {
        	Session::flash('alert-danger', 'Could not ' . $action . ' credit. Please try again.');
        	return Redirect::back();
        }
        $action = 'added';
        if ($request->amount < 0) {
        	$action = 'subtracted';
        }
        Session::flash('alert-success', 'Successfully ' . $action . ' ' . $request->amount . ' Credits.');
    	return Redirect::back();
    }

 	/**
     * Manually Edit Credit to User
     * @param Request $request
     * @return View
     */
    public function settings(Request $request)
	{
	 	$rules = [
            'tournament_participation'	=> 'filled|integer',
            'tournament_first'    		=> 'filled|integer',
            'tournament_second'    		=> 'filled|integer',
            'tournament_third'			=> 'filled|integer',
            'registration_event'		=> 'filled|integer',
            'registration_site'    		=> 'filled|integer',
        ];
      	$messages = [
            'tournament_participation.filled'	=> 'Tournament Participantion cannot be empty',
            'tournament_participation.integer'  => 'Tournament Participantion must be a number',
            'tournament_first.filled' 			=> 'Tournament First cannot be empty',
            'tournament_first.integer'  		=> 'Tournament First must be a number',
            'tournament_second.filled' 			=> 'Tournament Second cannot be empty',
            'tournament_second.integer'  		=> 'Tournament Second must be a number',
            'tournament_third.filled' 			=> 'Tournament Third cannot be empty',
            'tournament_third.integer'  		=> 'Tournament Third must be a number',
            'registration_event.filled' 		=> 'Event Registration cannot be empty',
            'registration_event.integer'  		=> 'Event Registration must be a number',
            'registration_site.filled' 			=> 'Site Registration cannot be empty',
            'registration_site.integer'  		=> 'Site Registration must be a number',
        ];
        $this->validate($request, $rules, $messages);

        $fail = false;
        if (
        	(
        		isset($request->tournament_participation) && 
        		!Settings::setCreditTournamentParticipation($request->tournament_participation)
        	) || (
        		isset($request->tournament_first) && 
	        	!Settings::setCreditTournamentFirst($request->tournament_first)
	        ) || (
        		isset($request->tournament_second) && 
	        	!Settings::setCreditTournamentSecond($request->tournament_second)
	        ) || (
        		isset($request->tournament_third) && 
        		!Settings::setCreditTournamentThird($request->tournament_third)
        	) || (
        		isset($request->registration_event) && 
        		!Settings::setCreditRegistrationEvent($request->registration_event)
        	) || (
        		isset($request->registration_site) && 
        		!Settings::setCreditRegistrationSite($request->registration_site)
    		)
        ) {
			Session::flash('alert-danger', 'Could not apply settings. Please try again.');
        	return Redirect::back();
        }
        Session::flash('alert-success', 'Successfully applied settings!');
    	return Redirect::back();
    }
}
