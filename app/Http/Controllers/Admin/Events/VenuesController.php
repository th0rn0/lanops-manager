<?php

namespace App\Http\Controllers\Admin\Events;

use DB;
use Auth;
use Session;
use Storage;
use Input;

use App\Event;
use App\EventVenue;
use App\EventVenueImage;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class VenuesController extends Controller
{
	/**
	 * Show Venues Index Page
	 * @return View
	 */
	public function index()
	{
		$venues = EventVenue::all();
		return view('admin.events.venues.index')->withVenues($venues);
	}

	/**
	 * Show Venue Page
	 * @param  EventVenue $venue
	 * @return View
	 */
	public function show(EventVenue $venue)
	{
		return view('admin.events.venues.show')->withVenue($venue);
	}

	/**
	 * Store Venue to Database
	 * @param  Request $request
	 * @return Redirect
	 */
	public function store(Request $request)
	{
		$rules = [
			'name'				=> 'required',
			'address_1'			=> 'required',
			'address_street' 	=> 'required',
			'address_city' 		=> 'required',
			'address_postcode' 	=> 'required',
			'image.*' 			=> 'image',
		];
		$messages = [
			'name|required' 			=> 'A Venue Name is Required',
			'address_1|required' 		=> 'A Address is Required',
			'address_street|required' 	=> 'A Street name is Required',
			'address_city|required' 	=> 'A City name is Required',
			'address_postcode|required' => 'A Postcode is Required',
			'image.*|image' 			=> 'Venue Image must be of Image type',
		];
		$this->validate($request, $rules, $messages);

		$venue = new EventVenue();
		$venue->display_name 		= $request->name;
		$venue->address_1 			= $request->address_1;
		$venue->address_2 			= $request->address_2;
		$venue->address_street 		= $request->address_street;
		$venue->address_city 		= $request->address_city;
		$venue->address_postcode 	= $request->address_postcode;
		$venue->address_country 	= $request->address_country;

		if (!$venue->save()) {
		 	Session::flash('alert-danger', 'Could not save!');
    		return Redirect::back();
		}
		if (Input::file('images')) {
			foreach(Input::file('images') as $image){
				$venue->images()->create([
					'path' => str_replace('public/', '/storage/', Storage::put('public/images/venues/' . $venue->slug, $image)),
				]);
			}
		}
	    Session::flash('alert-success', 'Successfully create venue!');
	    return Redirect::to('admin/venues/' . $venue->id);
	}

	/**
	 * Update Venue
	 * @param  EventVenue $venue  
	 * @param  Request    $request
	 * @return Redirect
	 */
	public function update(EventVenue $venue, Request $request)
	{
		if ($request->name) {
			$venue->display_name = $request->name;
		}
		if ($request->address_1) {
			$venue->address_1 = $request->address_1;
		}
		if ($request->address_2) {
			$venue->address_2 = $request->address_2;
		}
		if ($request->address_street) {
			$venue->address_street = $request->address_street;
		}
		if ($request->address_city) {
			$venue->address_city = $request->address_city;
		}
		if ($request->address_postcode) {
			$venue->address_postcode = $request->address_postcode;
		}
		if ($request->address_country) {
			$venue->address_country = $request->address_country;
		}

		if (Input::file('images')) {
			foreach(Input::file('images') as $image){
	      $venue->images()->create([
					'path' => str_replace('public/', '/storage/', Storage::put('public/images/venues/' . $venue->slug, $image)),
				]);
	    	}
		}
		if (!$venue->save()) {
			Session::flash('alert-danger', 'Could not update!');
			return Redirect::back();
		}
		Session::flash('alert-success', 'Successfully updated!');
  		return Redirect::back();
	}

	/**
	 * Delete Venue from Database
	 * @param  EventVenue $venue
	 * @return Redirect
	 */
	public function destroy(EventVenue $venue)
	{
		if (!$venue->events->isEmpty()) {
			Session::flash('alert-danger', 'Venue is used in events - cannot delete!');
			return Redirect::back();
		}

		if (!$venue->delete()) {
			Session::flash('alert-danger', 'Could not delete!');
			return Redirect::back();
	  	}
	  	Session::flash('alert-success', 'Successfully deleted!');
	  	return Redirect::to('/admin/venues');
	}

	/**
	 * Update Venue Image
	 * @param  EventVenue      $venue
	 * @param  EventVenueImage $image
	 * @param  Request         $request
	 * @return Redirect
	 */
	public function updateImage(EventVenue $venue, EventVenueImage $image, Request $request)
	{
		if ($request->description) {
			$image->description = $request->description;
		}
		if (!$image->save()) {
			Session::flash('alert-danger', 'Could not update!');
			return Redirect::back();
		}
		Session::flash('alert-success', 'Successfully updated!');
  		return Redirect::back();
	}

	/**
	 * Delete Image Venue
	 * @param  EventVenue      $venue 
	 * @param  EventVenueImage $image 
	 * @return Redirect                 
	 */
	public function destroyImage(EventVenue $venue, EventVenueImage $image) 
	{
		if (!$image->delete()) {
			Session::flash('alert-danger', 'Could not delete!');
			return Redirect::back();
	  	}
	  	Session::flash('alert-success', 'Successfully deleted!');
	  	return Redirect::back();
	}
}