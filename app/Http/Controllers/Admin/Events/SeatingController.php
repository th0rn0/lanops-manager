<?php

namespace App\Http\Controllers\Admin\Events;

use Illuminate\Http\Request;

use DB;
use Session;
use Storage;
use App\User;
use App\Event;
use App\EventTicket;
use App\EventSeating;
use App\EventSeatingPlan;
use App\EventParticipant;
use App\EventParticipantType;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redirect;

class SeatingController extends Controller
{
	/**
	 * Show Seating Index Page
	 * @param  Event  $event
	 * @return View
	 */
	public function index(Event $event)
	{
		return view('admin.events.seating.index')->withEvent($event);
	}

	/**
	 * Show Seating Page
	 * @param  Event            $event
	 * @param  EventSeatingPlan $seating_plan
	 * @return View
	 */
	public function show(Event $event, EventSeatingPlan $seating_plan)
	{
		return view('admin.events.seating.show')->withEvent($event)->withSeatingPlan($seating_plan);
	}

	/**
	 * Add Seating Plan to Database
	 * @param  Event   $event
	 * @param  Request $request
	 * @return Redirect
	 */
	public function store(Event $event, Request $request)
	{
		$rules = [
			"name"    => "required",
			"columns" => "required|integer",
			"rows"    => "required|integer",
			'image'   => 'image',
		];
		$messages =[
			'name|required'     => 'A Name is required',
			'columns|required'  => 'Columns is required',
			'columns|integer'   => 'Columns must be a number',
			'rows|required'     => 'Rows is required',
			'rows|integer'      => 'Rows must be a number',
			'image|image'       => 'Seating image must be a image',
		];
		$this->validate($request, $rules, $messages);

		$seating_plan           = new EventSeatingPlan;
		$seating_plan->event_id = $event->id;
		$seating_plan->name     = $request->name;
		$alphabet = range('A', 'Z');
		for ($i=0; $i < $request->columns; $i++) { 
			$seating_headers[] = $alphabet[$i];
		}
		$seating_plan->headers  = implode(', ', $seating_headers);
		$seating_plan->columns  = $request->columns;
		$seating_plan->rows     = $request->rows;

		if($request->file('image') !== NULL){
			$seating_plan->image_path = str_replace(
				'public/', 
				'/storage/', 
				Storage::put(
					'public/images/events/' . $event->slug . '/seating/' . $seating_plan->slug,
					$request->file('image')
				)
			);
		}

		if (!$seating_plan->save()) {
			Session::flash('alert-danger', 'Could not save!');
			return Redirect::back();
		}
		Session::flash('alert-success', 'Successfully saved!');
		return Redirect::to('admin/events/' . $event->slug . '/seating/' . $seating_plan->slug);
	}

	/**
	 * Update Seating Plan
	 * @param  Event            $event
	 * @param  EventSeatingPlan $seating_plan
	 * @param  Request          $request
	 * @return Redirect
	 */
	public function update(Event $event, EventSeatingPlan $seating_plan, Request $request)
	{
		$rules = [
			"columns" => "integer",
			"rows"    => "integer",
			'image'   => 'image',
			'status'  => 'in:draft,published',
			'locked'  => 'boolean',
		];
		$messages =[
			'columns|integer'   => 'Columns must be a number',
			'rows|integer'      => 'Rows must be a number',
			'image|image'       => 'Seating image must be a image',
			'status|in'         => 'Status must be draft or published',
			'locked|boolean'    => 'Locked must be a boolean',
		];
		$this->validate($request, $rules, $messages);

		$seating_plan->name   = @$request->name;
		$seating_plan->status = @$request->status;
		$seating_plan->locked = ($request->locked ? true : false);

		if ($seating_plan->columns != $request->columns || $seating_plan->rows != $request->rows) {
			$alphabet = range('A', 'Z');
			for ($i=0; $i < $request->columns; $i++) { 
				$seating_headers[] = $alphabet[$i];
			}
			$seating_plan->headers  = implode(',', $seating_headers);
			$seating_plan->columns  = $request->columns;
			$seating_plan->rows     = $request->rows;
		}

		if($request->file('image') !== NULL){
			Storage::delete($seating_plan->image_path); 
			$seating_plan->image_path = str_replace(
				'public/', 
				'/storage/', 
				Storage::put(
					'public/images/events/' . $event->slug . '/seating/' . $seating_plan->slug,
					$request->file('image')
				)
			);
		}
		if (!$seating_plan->save()) {
			Session::flash('alert-danger', 'Could not save!');
			return Redirect::back();
		}
		Session::flash('alert-success', 'Successfully updated!');
		return Redirect::back();
	}

	/**
	 * Delete Seating Plan
	 * @param  Event            $event
	 * @param  EventSeatingPlan $seating_plan
	 * @return Redirect
	 */
	public function destroy(Event $event, EventSeatingPlan $seating_plan)
	{
		if (!$seating_plan->delete()) {
			Session::flash('alert-danger', 'Could not delete!');
			return Redirect::back();
		}
		Session::flash('alert-success', 'Successfully deleted!');
		return Redirect::to('/admin/events/' . $event->slug . '/seating');
	}

	/**
	 * Seat Participant
	 * @param  Event            $event
	 * @param  EventSeatingPlan $seating_plan
	 * @param  Request          $request
	 * @return Redirect
	 */
	public function storeSeat(Event $event, EventSeatingPlan $seating_plan, Request $request)
	{
		if (
			!in_array(substr($request->seat_number_modal, 0, 1), explode(',', $seating_plan->headers)) ||
			substr($request->seat_number_modal, 1, 1) <= 0 ||
			substr($request->seat_number_modal, 1, 1) > $seating_plan->rows
		) {
			Session::flash('alert-danger', 'Invalid seat!');
			return Redirect::back();
		}

		if (isset($request->participant_id_modal) && trim($request->participant_id_modal) != '') {
			$clauses = ['event_participant_id' => $request->participant_id_modal];
			$previous_seat = EventSeating::where($clauses)->first();
			if($previous_seat != null){
				$previous_seat->delete();
			}
		}

		if (isset($request->participant_select_modal) && trim($request->participant_select_modal) != '') {
			$clauses = ['id' => $request->participant_select_modal];
			$participant = EventParticipant::where($clauses)->first();
			if (($participant->ticket && !$participant->ticket->seatable) ) {
				Session::flash('alert-danger', 'Ticket is not seatable!');
				return Redirect::back();
			}
		}

		$clauses = ['event_participant_id' => $request->participant_select_modal];
		$previous_seat = EventSeating::where($clauses)->first();
		if($previous_seat != null){
			$previous_seat->delete();
		}

		$clauses = ['seat' => $request->seat_number_modal, 'event_seating_plan_id' => $seating_plan->id];
		$seat = EventSeating::where($clauses)->first();
		if($seat != null){
			Session::flash('alert-danger', 'Seat is still occupied. Please try again!');
			return Redirect::back();
		}

		$new_seat                         = new EventSeating;
		$new_seat->seat                   = $request->seat_number_modal;
		$new_seat->event_participant_id   = $request->participant_select_modal;
		$new_seat->event_seating_plan_id  = $seating_plan->id;
		if (!$new_seat->save()) {
			Session::flash('alert-danger', 'Could not update!');
			return Redirect::back();
		}
		Session::flash('alert-success', 'Successfully updated!');
		return Redirect::back();
	}

	/**
	 * Remove Participant Seating
	 * @param  Event            $event
	 * @param  EventSeatingPlan $seating_plan
	 * @param  Request          $request
	 * @return Redirect
	 */
	public function destroySeat(Event $event, EventSeatingPlan $seating_plan, Request $request)
	{
		if (!$seat = $seating_plan->seats()->where('seat', $request->seat_number)->first()) {
			Session::flash('alert-danger', 'Could not find seat!');
			return Redirect::back();
		}
		if (!$seat->delete()) {
			Session::flash('alert-danger', 'Could clear seat!');
			return Redirect::back();
		}
		Session::flash('alert-success', 'Seat Updated!');
		return Redirect::back();
	}
}
