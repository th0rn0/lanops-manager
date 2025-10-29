<?php

namespace App\Http\Controllers\Admin;

use Auth;
use Session;

use App\Models\User;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class UsersController extends Controller
{
    /**
     * Show Users Index Page
     * @return View
     */
    public function index()
    {
        return view('admin.users.index')
            ->withUser(Auth::user())
            ->withUsers(User::paginate(20));
    }

    /**
     * Show User Page
     * @return View
     */
    public function show(User $user)
    {
        return view('admin.users.show')
            ->withUserShow($user)
            ->withPurchases($user->purchases()->paginate(10, ['*'], 'pu'));
    }

    /**
     * Update User
     * @param  User  $user
     * @return View
     */
    public function update(User $user, Request $request)
    {
        $rules = [
            'username'      => 'required|unique:users,username',
        ];
        $messages = [
            'username.unique'       => 'Username must be unique',
            'username.required'     => 'Username is required',
        ];

        $user->username = $request->username;
        $user->username_nice    = strtolower(str_replace(' ', '-', $request->username));
        if (!$user->save()) {
            Session::flash('alert-danger', 'Cannot update user!');
            return Redirect::back();
        }
        Session::flash('alert-success', 'Successfully updated user!');
        return Redirect::back();
    }

    /**
     * Grant User Admin
     * @param  User  $user
     * @return View
     */
    public function grantAdmin(User $user)
    {
        if (!Auth::user()->admin) {
            Session::flash('alert-danger', 'You do not have permissions to do this!');
            return Redirect::back();
        }
        $user->admin = true;
        if (!$user->save()) {
            Session::flash('alert-danger', 'Cannot grant admin!');
            return Redirect::back();
        }
        Session::flash('alert-success', 'Successfully updated user!');
        return Redirect::back();
    }

    /**
     * Remove User Admin
     * @param  User  $user
     * @return View
     */
    public function removeAdmin(User $user)
    {
        if (!Auth::user()->admin) {
            Session::flash('alert-danger', 'You do not have permissions to do this!');
            return Redirect::back();
        }
        $user->admin = false;
        if (!$user->save()) {
            Session::flash('alert-danger', 'Cannot remove admin!');
            return Redirect::back();
        }
        Session::flash('alert-success', 'Successfully updated user!');
        return Redirect::back();
    }

    /**
     * Ban User
     * @param  User  $user
     * @return View
     */
    public function ban(User $user)
    {
        if (!Auth::user()->admin) {
            Session::flash('alert-danger', 'You do not have permissions to do this!');
            return Redirect::back();
        }
        $user->banned = true;
        if (!$user->save()) {
            Session::flash('alert-danger', 'Cannot ban user!');
            return Redirect::back();
        }
        Session::flash('alert-success', 'Successfully banned user!');
        return Redirect::back();
    }

    /**
     * Unban User
     * @param  User  $user
     * @return View
     */
    public function unban(User $user)
    {
        if (!Auth::user()->admin) {
            Session::flash('alert-danger', 'You do not have permissions to do this!');
            return Redirect::back();
        }
        $user->banned = false;
        if (!$user->save()) {
            Session::flash('alert-danger', 'Cannot unban user!');
            return Redirect::back();
        }
        Session::flash('alert-success', 'Successfully unbanned user!');
        return Redirect::back();
    }

    public function generalReferralCodes()
    {
        $users = User::all();
        foreach ($users as $user) {
            $user->referral_code = User::generateReferralCode();
            $user->save();
        }
    }
}
