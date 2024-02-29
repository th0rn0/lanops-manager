<?php

namespace App\Http\Controllers;

use Auth;

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
        $purchases = $user->purchases()->paginate(5, ['*'], 'pu');
        $tickets = $user->eventParticipants()->paginate(5, ['*'], 'ti');
        return view("accounts.index")
            ->withUser($user)
            ->withPurchases($purchases)
            ->withEventParticipants($tickets)
        ;

    }

    public function update(Request $request)
    {
        $rules = [
            'firstname'     => 'filled',
            'surname'       => 'filled',
            'email'         => 'filled|email',
            'password1'     => 'same:password2',
            'password2'     => 'same:password1',
        ];
        $messages = [
            'email.filled'      => 'Email Cannot be blank.',
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

        $user->email = @$request->email;
        $user->firstname = @$request->firstname;
        $user->surname = @$request->surname;

        if (!$user->save()) {
            return Redirect::back()->withFail("Oops, Something went Wrong.");
        }
        return Redirect::back()->withSuccess('Account successfully updated!');
    }
}
