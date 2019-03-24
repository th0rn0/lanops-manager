<?php

namespace App\Http\Controllers\Admin;

use DB;
use Auth;
use Session;

use App\User;
use App\Poll;
use App\PollOption;
use App\GalleryAlbum;
use App\GalleryAlbumImage;

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
		return view('admin.polls.index')->withPolls(Poll::all());
	}

	/**
	 * Show Poll Page
	 * @param  Poll  $poll
	 * @return View
	 */
	public function show(Poll $poll)
	{
		return view('admin.polls.show')->withPoll($poll);
	}

	public function update(Poll $poll, Request $request)
	{
		$rules = [
			'name'			=> 'filled',
			'status'		=> 'in:draft,preview,published',
		];
		$messages = [
			'name.filled'			=> 'Name cannot be empty',
			'status.in' 			=> 'Status must be draft, preview or published',
		];
		$this->validate($request, $rules, $messages);
		$poll->name = $request->name;
		$poll->status = $request->status;
		$poll->description = $request->description;
		if (!$poll->save()) {
			Session::flash('alert-danger', 'Cannot update Poll!');
			return Redirect::to('/admin/polls/' . $poll->slug);
		}
		Session::flash('alert-success', 'Successfully updated Poll!');
		return Redirect::to('/admin/polls/' . $poll->slug);
	}

	/**
	 * Add Poll to Database
	 * @param  Request $request
	 * @return Redirect
	 */
	public function store(Request $request)
	{
		$rules = [
			'name'			=> 'required',
			'options'		=> 'filled|array',
		];
		$messages = [
			'name.required'			=> 'Name is required',
			'options.filled'		=> 'Options cannot be empty',
			'options.array'			=> 'Options must be an arrau',
		];
		$this->validate($request, $rules, $messages);
		$poll = new Poll;
		$poll->name = $request->name;
		$poll->user_id = Auth::id();
		$poll->description = $request->description;
		$poll->allow_options_multi = ($request->allow_options_multi ? true : false);
		$poll->allow_options_user = ($request->allow_options_user ? true : false);
		if (!$poll->save()) {
			Session::flash('alert-danger', 'Cannot create Poll!');
			return Redirect::back();
		}
		if (isset($requests->options)) {
			foreach ($request->options as $option) {
				if (!$poll->addOption($option)) {
					$poll->delete();
					Session::flash('alert-danger', 'Cannot create Poll!');
					return Redirect::back();
				}
			}
		}
		Session::flash('alert-success', 'Successfully created Poll!');
		return Redirect::to('/admin/polls/' . $poll->slug);
	}

	/**
	 * Delete from the to Database
	 * @param  Poll $poll
	 * @return Redirect
	 */
	public function destroy(Poll $poll)
	{
		if (!$poll->delete()) {
			Session::flash('alert-danger', 'Cannot delete Poll!');
			return Redirect::back();
		}
		Session::flash('alert-success', 'Successfully deleted Poll!');
		return Redirect::to('/admin/polls/');
	}

	/**
	 * Add Poll Option to the Database
	 * @param  Poll $poll
	 * @param  Request $request
	 * @return Redirect
	 */
	public function storeOption(Poll $poll, Request $request)
	{
		$rules = [
			'name'			=> 'required|filled',
		];
		$messages = [
			'name.required'		=> 'Name is required',
			'name.filled'		=> 'Name cannot be empty',
		];
		$this->validate($request, $rules, $messages);
		if (!$poll->addOption($request->name)) {
			Session::flash('alert-danger', 'Cannot create Option!');
			return Redirect::back();
		}
		Session::flash('alert-success', 'Successfully created Option!');
		return Redirect::to('/admin/polls/' . $poll->slug);
	}

	/**
	 * Delete Poll Option from the the Database
	 * @param  Poll $poll
	 * @param  PollOption $option
	 * @return Redirect
	 */
	public function destroyOption(Poll $poll, PollOption $option)
	{
		if (!$option->delete()) {
			Session::flash('alert-danger', 'Cannot delete Option!');
			return Redirect::to('/admin/polls/' . $poll->slug);
		}
		Session::flash('alert-success', 'Successfully deleted Option!');
		return Redirect::to('/admin/polls/' . $poll->slug);
	}

	public function endPoll(Poll $poll, Request $request)
	{
		if (!$poll->endPoll()) {
			Session::flash('alert-danger', 'Cannot end Poll!');
			return Redirect::to('/admin/polls/' . $poll->slug);
		}
		Session::flash('alert-success', 'Successfully ended Poll!');
		return Redirect::to('/admin/polls/' . $poll->slug);
	}
}
