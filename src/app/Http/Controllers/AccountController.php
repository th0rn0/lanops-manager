<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use Settings;
use Colors;

use App\Http\Requests;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;


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
            $creditLogs = $user->creditLogs()->paginate(5, ['*'], 'cl');
        }
        $purchases = $user->purchases()->paginate(5, ['*'], 'pu');
        $tickets = $user->eventParticipants()->paginate(5, ['*'], 'ti');
        return view("accounts.index")
            ->withUser($user)
            ->withCreditLogs($creditLogs)
            ->withPurchases($purchases)
            ->withEventParticipants($tickets)
        ;

    }

    public function update(Request $request)
    {
        $rules = [
            'firstname'     => 'filled',
            'surname'       => 'filled',
            'password1'     => 'same:password2',
            'password2'     => 'same:password1',
        ];
        $messages = [
            'firstname.filled'  => 'Firstname Cannot be blank.',
            'surname.filled'    => 'Surname Cannot be blank.',
            'email.email'       => 'Email must be a valid Email Address.',
            'password1.same'    => 'Passwords must be the same.',
            'password2.same'    => 'Passwords must be the same.',
        ];
        $this->validate($request, $rules, $messages);

        $user = Auth::user();
        if (isset($request->password1) && $request->password1 != null) {
            $rules = [
                'password1'     => 'same:password2|min:8',
                'password2'     => 'same:password1|min:8',
            ];
            $messages = [
                'password1.same'    => 'Passwords must be the same.',
                'password1.min'     => 'Password must be atleast 8 characters long.',
                'password2.same'    => 'Passwords must be the same.',
                'password2.min'     => 'Password must be atleast 8 characters long.',
            ];
            $this->validate($request, $rules, $messages);
            $user->password = Hash::make($request->password1);
        }

        $user->firstname = @$request->firstname;
        $user->surname = @$request->surname;

        if (!$user->save()) {
            return Redirect::back()->withFail("Oops, Something went Wrong.");
        }
        return Redirect::back()->withSuccess('Account successfully updated!');
    }

    public function updateMail(Request $request)
    {
        $rules = [
            'email'         => 'filled|email',
        ];
        $messages = [
            'email.filled'      => 'Email Cannot be blank.',
        ];
        $this->validate($request, $rules, $messages);

        $user = Auth::user();

        $user->email_verified_at = null;

        $user->email = @$request->email;

        if (!$user->save()) {
            return Redirect::back()->withFail("Oops, Something went Wrong while updating the user.");
        }

        $user->sendEmailVerificationNotification();

        return Redirect::back()->withSuccess('Email updated but you need to confirm your email Adress !');
    }
}



