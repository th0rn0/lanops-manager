<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use DB;
use Auth;
use App\User;
use App\Event;
use App\EventParticipant;
use App\EventTicket;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class AdminController extends Controller
{
	/**
	 * Show Admin Index Page
	 * @return view
	 */
	public function index()
	{
		$user = Auth::user();
		$events = Event::all();
		return view('admin.index')->withUser($user)->withEvents($events);  
	}
}
