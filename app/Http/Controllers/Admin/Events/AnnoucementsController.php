<?php

namespace App\Http\Controllers\Admin\Events;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

use DB;
use Auth;
use Session;
use Storage;
use App\Event;
use App\EventAnnoucement;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class AnnoucementsController extends Controller
{
	/**
	 * Add Annoucement to Datbase
	 * @param  Request $request
	 * @param  Event   $event
	 * @return Redirect
	 */
	public function store(Request $request, Event $event)
	{
		$rules = [
			'message' => 'required'
		];
		$messages = [
			'message|required' => 'Some Text is required',
		];
		$this->validate($request, $rules, $messages);

		$annoucement = new EventAnnoucement();
		$annoucement->message = $request->message;
		$annoucement->event_id = $event->id;
		$annoucement->save();

		Session::flash('message', 'Successfully saved!');
		return Redirect::to('admin/events/' . $event->slug);
	}

	/**
	 * Update Annoucement
	 * @param  Request          $request
	 * @param  Event            $event
	 * @param  EventAnnoucement $annoucement
	 * @return Redirect
	 */
	public function update(Request $request, Event $event, EventAnnoucement $annoucement)
	{
		$rules = [
			'message' => 'required'
		];
		$messages = [
			'message|required' => 'Some Text is required',
		];
		$this->validate($request, $rules, $messages);

		$annoucement->message = $request->message;
		$annoucement->save();

		Session::flash('message', 'Successfully saved!');
		return Redirect::to('admin/events/' . $event->slug);
	}

	/**
	 * Delete Annoucement from Database
	 * @param  Event            $event
	 * @param  EventAnnoucement $annoucement
	 * @return Redirect
	 */
	public function destroy(Event $event, EventAnnoucement $annoucement)
	{
		$annoucement->delete();
		session::flash('message', 'Successfully deleted!');
		return Redirect::to('admin/events/' . $event->slug);
	}
}