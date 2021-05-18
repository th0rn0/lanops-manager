<?php

namespace App\Http\Controllers\Admin;

use DB;
use Auth;
use Settings;
use Colors;
use Session;

use App\User;
use App\Event;
use App\GalleryAlbum;
use App\GalleryAlbumImage;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;

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
        $creditLogs = false;
        if (Settings::isCreditEnabled()) {
            $creditLogs = $user->creditLogs()->paginate(5, ['*'], 'cl');
        }
        return view('admin.users.show')
            ->withUserShow($user)
            ->withCreditLogs($creditLogs)
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
     * Unauthorize thirdparty
     * @param  User  $user
     * @param  String  $method
     * @return View
     */
    public function unauthorizeThirdparty(User $user, String $method)
    {
        if (!Auth::user()->admin) {
            Session::flash('alert-danger', 'You do not have permissions to do this!');
            return Redirect::back();
        }

        switch ($method) {
            case 'steam':
                $user->steamname = null;
                $user->steamid = null;
                $user->avatar = null;
                break;
            default:
                Session::flash('alert-danger', 'Cannot remove thirdparty authentication, no method selected!!');
                return Redirect::back();
                break;
        }

        if (!$user->save()) {
            Session::flash('alert-danger', 'Cannot remove thirdparty authentication!');
            return Redirect::back();
        }
        Session::flash('alert-success', 'Successfully removed thirdparty authentication!');
        return Redirect::back();
    }


    /**
     * Remove User 
     * @param  User  $user
     * @return View
     */
    public function remove(User $user)
    {
        if (!Auth::user()->admin) {
            Session::flash('alert-danger', 'You do not have permissions to do this!');
            return Redirect::back();
        }
        if (!$user->delete()) {
            Session::flash('alert-danger', 'Cannot remove user!');
            return Redirect::back();
        }
        Session::flash('alert-success', 'Successfully removed user!');
        return Redirect::to('/admin/users/');
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
