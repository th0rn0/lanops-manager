<?php

namespace App\Http\Controllers\Admin\Events;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

use DB;
use Auth;
use Session;
use Storage;
use App\Event;
use App\EventInformation;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class InformationController extends Controller
{
	/**
	 * Add Information to Database
	 * @param  Request $request
	 * @param  Event   $event
	 * @return Redirect
	 */
	public function store(Request $request, Event $event)
	{
		$rules = [
			'title' => 'required',
			'text' => 'required',
			'image' => 'image',
		];
		$messages = [
			'title|required' => 'A Title is required',
			'text|required' => 'Some Text is required',
			'image|image' => 'The file must be a Image',
		];
		$this->validate($request, $rules, $messages);

		$information = new EventInformation();
		$information->title = $request->title;
		$information->text = $request->text;
		$information->event_id = $event->id;
	 	if($request->file('image') !== NULL){
			$information->image = str_replace(
				'public/', 
				'/storage/', 
				Storage::put(
					'public/images/events/' . $event->slug . '/info', 
					$request->file('image')
				)
			);
	    }
	    $information->save();

        Session::flash('alert-success', 'Successfully saved!');
        return Redirect::to('admin/events/' . $event->id);
	}

	/**
	 * Update Information
	 * @param  Request          $request    
	 * @param  EventInformation $information
	 * @return Redirect
	 */
	public function update(Request $request, EventInformation $information)
	{
		$rules = [
			'image' => 'image',
		];
		$messages = [
			'image|image' => 'The file must be a Image',
		];
		$this->validate($request, $rules, $messages);

		$information->title = $request->title;
		$information->text = $request->text;
		if($request->file('image') !== NULL){
			Storage::delete($information->image_path);
			$information->image_path = str_replace(
				'public/', 
				'/storage/', 
				Storage::put('public/images/events/' . $information->event->slug . '/info', 
					$request->file('image')
				)
			);
	    }
	    $information->save();
        Session::flash('alert-success', 'Successfully saved!');
        return Redirect::to('admin/events/' . $information->event->id);
	}

	/**
	 * Delete Information from the Database
	 * @param  EventInformation $information
	 * @return Redirect
	 */
	public function destroy(EventInformation $information)
	{
		$information->delete();
		session::flash('alert-success', 'Successfully deleted!');
        return Redirect::to('admin/events/' . $information->event->id);
	}
}