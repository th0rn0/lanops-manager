<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;
use App\Http\Requests;

class AccountController extends Controller
{
	/**
	 * Show Account Index Page
	 * @return View
	 */
	public function index()
	{
		$user = Auth::user();
		return view("accounts.index")->withUser($user);   
	}

}
