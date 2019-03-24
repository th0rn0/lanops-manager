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
		if ($option->hasVoted()) {
			Session::flash('alert-danger', 'Cannot Vote Twice!');
			return Redirect::back();
		}
		if (!$poll->allow_options_multi) {
			foreach ($poll->options as $poll_option) {
				if (!$poll_option->votes->where('user_id', Auth::id())->isEmpty()) {
					Session::flash('alert-danger', 'Cannot Vote Twice!');
					return Redirect::back();
				}
			}
		}
		if (!$option->vote()) {
			Session::flash('alert-danger', 'Cannot Vote!');
			return Redirect::back();
		}
		Session::flash('alert-success', 'Successfully Voted!');
		return Redirect::back();   
	}

	/**
	 * Abstain on Poll Option
	 * @return Redirect
	 */
	public function abstain(Poll $poll, PollOption $option)
	{
		if (!$option->abstain()) {
			Session::flash('alert-danger', 'Cannot remove Vote!');
			return Redirect::back();
		}
		Session::flash('alert-success', 'Successfully removed Vote!');
		return Redirect::back();   
	}

	public function storeOption(Poll $poll, Request $request)
	{
		if (!$poll->allow_options_user) {
			Session::flash('alert-danger', 'Only Admins can add options to this Poll!');
			return Redirect::back();
		}
		$rules = [
			'name'			=> 'required|filled',
		];
		$messages = [
			'name.required'		=> 'Option is required',
			'name.filled'		=> 'Option cannot be empty',
		];
		$this->validate($request, $rules, $messages);
		if ($poll->options->where('name', $request->name)->count() > 0) {
			Session::flash('alert-danger', 'Cannot create Option! It Already Exists.');
			return Redirect::back();
		}
		if (!$poll->addOption($request->name)) {
			Session::flash('alert-danger', 'Cannot create Option!');
			return Redirect::back();
		}
		Session::flash('alert-success', 'Successfully created Option!');
		return Redirect::to('/polls/' . $poll->slug);
	}

}
