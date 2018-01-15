<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use DB;
use Auth;
use App\User;
use App\Event;
use App\GalleryAlbum;
use App\GalleryAlbumImage;

use App\Http\Requests;
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
		$user = Auth::user();
		$users = User::all();
		return view('admin.users.index')->withUser($user)->withUsers($users);  
	}
}
