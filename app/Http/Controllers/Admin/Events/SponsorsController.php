<?php

namespace App\Http\Controllers\Admin\Events;

use Illuminate\Http\Request;

use DB;
use App\User;
use App\Event;
use App\EventTicket;
use App\EventSponsor;
use App\EventSeating;
use App\EventParticipant;
use App\EventParticipantType;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;

class SponsorsController extends Controller
{
	/**
	 * Add Sponsor to Database
	 * @param  Request $request
	 * @param  Event   $event
	 * @return Redirect
	 */
	public function store(Request $request, Event $event)
	{
		$sponsor = new EventSponsor;

		$destination_path = 'uploads/event/sponsors/';
		$sponsor->event_id = $event_id;
		$sponsor->name = $request->sponsor_name;
		if($request->file('sponsor_image') !== NULL){
			$sponsor->image_path = str_replace(
				'public/', 
				'/storage/', 
				Storage::put('public/images/events/' . $event->slug . '/sponsors', 
					$request->file('sponsor_image')
				)
			);
		}
		$sponsor->save();
		return Redirect::to('admin/events/' . $event->slug);
	}
}