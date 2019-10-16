<?php

namespace App\Http\Controllers\Admin;

use DB;
use Auth;
use Settings;

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
            ->withUsers(User::paginate(20))
        ;
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
            ->withUser($user)
            ->withCreditLogs($creditLogs)
            ->withPurchases($user->purchases()->paginate(10, ['*'], 'pu'))
        ;
    }
}
