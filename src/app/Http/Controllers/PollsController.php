<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use Session;

use App\Poll;
use App\PollOption;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;

class PollsController extends Controller
{
	/**
	 * Show Polls Index Page
	 * @return View
	 */
	public function index()
	{
		return view("polls.index")->withPolls(Poll::all());   
	}

	/**
	 * Show Poll Page
	 * @return View
	 */
	public function show(Poll $poll)
	{
		return view("polls.show")->withPoll($poll);   
	}

	/**
	 * Vote on Poll Option
	 * @return Redirect
	 */
	public function vote(Poll $poll, PollOption $option)
	{
		if ($option->votes->pluck('user_id', Auth::id())) {
			Session::flash('alert-danger', 'Cannot Vote Twice!');
			return Redirect::back();
		}
		if (!$option->vote()) {
			Session::flash('alert-danger', 'Cannot Vote!');
			return Redirect::back();
		}
		Session::flash('alert-success', 'Successfully Vote!');
		return Redirect::back();   
	}

	/**
	 * Abstain on Poll Option
	 * @return Redirect
	 */
	public function abstain(Poll $poll, PollOption $option)
	{
		if (!$poll->abstain()) {
			Session::flash('alert-danger', 'Cannot remove Vote!');
			return Redirect::back();
		}
		Session::flash('alert-success', 'Successfully removed Vote!');
		return Redirect::back();   
	}

}
