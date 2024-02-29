<?php

namespace App\Http\Controllers\Admin;

use Auth;
use Session;

use App\User;

use App\Http\Controllers\Controller;

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
}
