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
        ;
    }

    /**
     * Manually Add Credit to User
     * @return View
     */
    public function add(Request $request)
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

        if (!User::where('id', $request->user_id)->first()->addCredit($request->amount, true)) {
        	Session::flash('alert-danger', 'Could not add credit. Please try again');
        	return Redirect::back();
        }
        Session::flash('alert-success', 'Successfully added ' . $request->amount . ' Credits.');
    	return Redirect::back();
    }
}
