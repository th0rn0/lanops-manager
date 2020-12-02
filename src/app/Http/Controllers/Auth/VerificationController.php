<?php

namespace App\Http\Controllers\Auth;
use Auth;


use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;


class VerificationController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Email Verification Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling email verification for any
    | user that recently registered with the application. Emails may also
    | be re-sent if the user didn't receive the original email message.
    |
    */

    // use VerifiesEmails;

    

    /**
     * Where to redirect users after verification.
     *
     * @var string
     */
    protected $redirectTo = '/account';


    public function show (){

        $user = Auth::user();

        if ($user->email == NULL)
        {
        return redirect('/account/email');
        }
        return view('auth.verify');
    }

    public function verify (EmailVerificationRequest $request) {
        $request->fulfill();

        return redirect('/account');
    }

    public function resend (Request $request) {
        $request->user()->sendEmailVerificationNotification();

        return back()->with('status', 'verification-link-sent');
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }
}
