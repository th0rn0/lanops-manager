<?php

namespace App\Http\Controllers\Auth;

use Auth;

use Session;

use App\User;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

use Invisnik\LaravelSteamAuth\SteamAuth;

use Carbon\Carbon;

class SteamController extends Controller
{
    private $steam;

    /**
     * @param SteamAuth $steam
     */
    public function __construct(SteamAuth $steam)
    {
        $this->steam = $steam;
    }

    /**
     * Login User
     * @return Redirect
     */
    public function login()
    {
        if ($this->steam->validate()) {
            $info = $this->steam->getUserInfo();
            if (!is_null($info)) {
                $user = User::where('steamid', $info->steamID64)->first();
                if (!is_null($user)) {
                    if ($user->banned) {
                        Session::flash('alert-danger', 'You have been banned!');
                        return Redirect::back()->withError('You have been banned.');
                    }
                    //username found... Log user in
                    Auth::login($user, true);
                    //Check if the user has changed their steam details
                    $steam_changes = false;
                    if ($info->personaname != $user->steamname) {
                        $user->steamname = $info->personaname;
                        $steam_changes = true;
                    }
                    if ($info->avatarfull != $user->avatar) {
                        $user->avatar = $info->avatarfull;
                        $steam_changes = true;
                    }
                    $user->last_login = Carbon::now()->toDateTimeString();
                    $user->save();
                    return redirect('/'); // redirect to site
                } else {
                    $user = [
                            'steamname'     => $info->personaname,
                            'avatar'        => $info->avatarfull,
                            'steamid'       => $info->steamID64,
                    ];
                    Session::put('user', $user);
                    Session::save();
                    return Redirect('/register/steam');
                }
            }
        } else {
            return $this->steam->redirect(); // redirect to Steam login page
        }
    }

    /**
     * Update user details.
     * @param  Request $request
     * @param  User    $user
     * @return Redirect
     */
    public function update(Request $request, User $user)
    {
        $this->validate($request, [
                'fistname'  => 'string',
                'surname'   => 'string',
                'username'  => 'unique:users,username',
        ]);
        $user->firstname = $request->firstname;
        $user->surname = $request->surname;
        $user->username = $request->username;
        $user->username_nice = strtolower(str_replace(' ', '-', $request->username));
        if ($user->save()) {
            return Redirect('/account');
        }
        Auth::logout();
        Session::flash('alert-danger', 'Something went wrong. Please Try again later!');
        return Redirect('/');
    }

    /**
     * Delete Account
     * @return Redirect
     */
    public function destroy()
    {
        $user = Auth::user();
        if ($user && $user->delete()) {
            Session::flash('alert-success', 'Account Deleted!');
            return Redirect::to('/');
        }
        Session::flash('alert-danger', 'Could not delete Account!');
        return Redirect::to('/account');
    }
}
