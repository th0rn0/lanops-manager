<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use DB;
use Auth;
use Session;
use Redirect;
use Settings;
use Input;
use App\User;
use App\Setting;
use App\Event;
use App\EventParticipant;
use App\EventTicket;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class SettingsController extends Controller
{
	/**
	 * Show Settings Index Page
	 * @return Redirect
	 */
	public function index()
	{
		return view('admin.settings.index')->withSettings(Setting::all());
	}
	
	/**
	 * Update Settings
	 * @param  Request $request
	 * @return Redirect
	 */
	public function update(Request $request)
	{
		$rules = [
			'terms_and_conditions'      => 'filled',
			'org_name'                  => 'filled',
			'about_main'                => 'filled',
			'about_short'               => 'filled',
			'about_our_aim'             => 'filled',
			'about_who'                 => 'filled',
			'currency'                  => 'in:GBP,USD,EUR',
			'participant_count_offset'  => 'numeric',
			'lan_count_offset'          => 'numeric',
		];
		$messages = [
			'terms_and_conditions|filled'       => 'Terms And Conditions cannot be empty',
			'org_name|filled'                   => 'Org Name cannot be empty',
			'about_main|filled'                 => 'About Main cannot be empty',
			'about_short|filled'                => 'About Short cannot be empty',
			'about_our_aim|filled'              => 'About Our Aim cannot be empty',
			'about_who|filled'                  => 'About Whos who cannot be empty',
			'currency|in'                       => 'Currency must be GBP, USD or EUR',
			'participant_count_offset|numeric'  => 'Participant Count Offset must be a number',
			'lan_count_offset|numeric'          => 'Lan Count Offset must be a number',
		];
		$this->validate($request, $rules, $messages);

		if (isset($request->steam) && !Settings::setSteamLink($request->steam)) {
			Session::flash('alert-danger', 'Could not update!');
			return Redirect::back();
		}

		if (isset($request->teamspeak) && !Settings::setTeamspeakLink($request->teamspeak)) {
			Session::flash('alert-danger', 'Could not update!');
			return Redirect::back();
		}

		if (isset($request->discord) && !Settings::setDiscordLink($request->discord)) {
			Session::flash('alert-danger', 'Could not update!');
			return Redirect::back();
		}

		if (isset($request->reddit) && !Settings::setRedditLink($request->reddit)) {
			Session::flash('alert-danger', 'Could not update!');
			return Redirect::back();
		}

		if (isset($request->facebook) && !Settings::setFacebookLink($request->facebook)) {
			Session::flash('alert-danger', 'Could not update!');
			return Redirect::back();
		}
		
		if (isset($request->participant_count_offset) && !Settings::setParticipantCountOffset($request->participant_count_offset)) {
			Session::flash('alert-danger', 'Could not update!');
			return Redirect::back();
		}

		if (isset($request->lan_count_offset) && !Settings::setLanCountOffset($request->lan_count_offset)) {
			Session::flash('alert-danger', 'Could not update!');
			return Redirect::back();
		}

		if (isset($request->about_main) && !Settings::setAboutMain($request->about_main)) {
			Session::flash('alert-danger', 'Could not update!');
			return Redirect::back();
		}

		if (isset($request->about_short) && !Settings::setAboutShort($request->about_short)) {
			Session::flash('alert-danger', 'Could not update!');
			return Redirect::back();
		}

		if (isset($request->about_our_aim) && !Settings::setAboutOurAim($request->about_our_aim)) {
			Session::flash('alert-danger', 'Could not update!');
			return Redirect::back();
		}

		if (isset($request->about_who) && !Settings::setAboutWho($request->about_who)) {
			Session::flash('alert-danger', 'Could not update!');
			return Redirect::back();
		}

		if (isset($request->terms_and_conditions) && !Settings::setTermsAndConditions($request->terms_and_conditions)) {
			Session::flash('alert-danger', 'Could not update!');
			return Redirect::back();
		}

		if (isset($request->currency) && !Settings::setCurrency($request->currency)) {
			Session::flash('alert-danger', 'Could not update!');
			return Redirect::back();
		}

		if (isset($request->org_name) && !Settings::setOrgName($request->org_name)) {
			Session::flash('alert-danger', 'Could not update!');
			return Redirect::back();
		}

		if (Input::file('org_logo') && !Settings::setOrgLogo(Input::file('org_logo'))) {
			Session::flash('alert-danger', 'Could not update!');
			return Redirect::back();
		}

		if (Input::file('org_favicon') && !Settings::setOrgFavicon(Input::file('org_favicon'))) {
			Session::flash('alert-danger', 'Could not update!');
			return Redirect::back();
		}

		Session::flash('alert-success', 'Successfully updated!');
		return Redirect::back(); 

		$setting->value = $request->setting;
		if (!$setting->save()) {
			Session::flash('alert-danger', 'Could not Save!');
			return Redirect::back();
		}
		Session::flash('alert-success', 'Successfully updated!');
		return Redirect::back();
	}

	/**
	 * Delete Setting
	 * @param  Setting $setting
	 * @return Redirect
	 */
	public function destroy(Setting $setting)
	{
		if (!$setting->default && !$setting->delete()) {
			Session::flash('alert-danger', 'Could not delete!');
			return Redirect::back();
		}
		Session::flash('alert-success', 'Successfully deleted!');
		return Redirect::back(); 
	}

	/**
	 * Regenerate QR codes for Event Participants
	 * @return Redirect
	 */
	public function regenerateQRCodes()
	{
		$count = 0;
		foreach (Event::all() as $event) {
			if (!$event->eventParticipants->isEmpty()) {
				foreach ($event->eventParticipants as $participant) {
					//DEBUG - Delete old images
					$participant->generateQRCode();
					$participant->save();
					$count++;
				}
			}
		}
		Session::flash('alert-success', 'Successfully regenerated ' . $count . ' QR Codes!');
		return Redirect::back(); 

	}
}
