<?php

namespace App\Http\Controllers\Admin\News;

use Illuminate\Http\Request;

use DB;
use Auth;
use App\News;
use App\User;
use App\Event;
use App\EventParticipant;
use App\EventTicket;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class NewsController extends Controller
{
	/*
	|--------------------------------------------------------------------------
	| News Controller
	|--------------------------------------------------------------------------
	|
	| This controller handles the registration of new News posts, as well as the
	| modifacation
	|
	*/

	/**
	 * Show News Index Page
	 * @return Redirect
	 */
	public function index()
	{
		$user = Auth::user();
		return view('admin.news.main')->withUser($user);  
	}
}
