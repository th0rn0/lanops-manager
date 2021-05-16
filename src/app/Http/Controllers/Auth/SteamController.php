<?php

namespace App\Http\Controllers\Auth;

use Auth;

use Session;
use Settings;
use Validator;


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
                    if ((!isset($user->email) || $user->email == null ) &&  Settings::isAuthSteamRequireEmailEnabled())
                    {
                    $user->email_verified_at    = null;
                    $user->save();
                    return redirect('/account/email'); // redirect to site
                    }

                    return redirect('/'); // redirect to site
                } else {
                    if (!Auth::user())
                    {
                        $user = [
                                'steamname'     => $info->personaname,
                                'avatar'        => $info->avatarfull,
                                'steamid'       => $info->steamID64,
                        ];
                        Session::put('user', $user);
                        Session::save();
                        return Redirect('/register/steam');
                    }
                    else
                    {
                        if (!Auth::user()->steamid)
                        {
                           return $this->addtoexistingaccount($info, Auth::user());

                        }
                        else
                        {
                            Session::flash('alert-danger', 'Another steamid is already set in your account, remove it first in your account settings!');
                            return Redirect::to('/account/')->withError('Another steamid is already set in your account, remove it first in your account settings!');
                        }

                    }


                }
            }
        } else {
            return $this->steam->redirect(); // redirect to Steam login page
        }
    }

    /**
     * Add Steam account to existing account.
     * @param  $info
     * @param  User    $user
     * @return Redirect
     */
    private function addtoexistingaccount($info, User $user)
    {
        print_r($info->toArray());
        $validator = Validator::make($info->toArray(), [
            'personaname'   => 'required|string',
            'avatarfull'    => 'required|string',
            'steamID64' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect('/account/')
                        ->withErrors($validator)
                        ->withInput();
        }

        $user->steamname = $info->personaname;
        $user->avatar = $info->avatarfull;
        $user->steamid = $info->steamID64;
        if ($user->save()) {
            Session::flash('alert-success', "Successfully added steam account!");
            return Redirect('/account');
        }
        Session::flash('alert-danger', 'Saving user failed!');
        return Redirect::to('/account/')->withError('Saving user failed!');
        
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
