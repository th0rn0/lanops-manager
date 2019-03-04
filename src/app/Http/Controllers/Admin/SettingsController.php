<?php

namespace App\Http\Controllers\Admin;

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
use Facebook\Facebook;

use Illuminate\Http\Request;

class SettingsController extends Controller
{
	/**
	 * Show Settings Index Page
	 * @return Redirect
	 */
	public function index(Facebook $facebook)
	{
		// Build Permissions and callbacks for Social Media
		$facebook_permissions = ['manage_pages','publish_pages'];
		// TODO - Wrap in if to see if its already been linked
		// dd($facebook->getRedirectLoginHelper()->getLoginUrl(url('/') . '/admin/settings/link/facebook', $facebook_permissions));
		$facebook_callback = null;
		$facebook_callback = $facebook->getRedirectLoginHelper()->getLoginUrl(url('/admin/settings/link/facebook'), $facebook_permissions);
		return view('admin.settings.index')
			->withSettings(Setting::all())
			->withFacebookCallback($facebook_callback)
		;
	}
	
	/**
	 * Update Settings
	 * @param  Request $request
	 * @return Redirect
	 */
	public function update(Request $request)
	{
		$rules = [
			'terms_and_conditions'		=> 'filled',
			'org_name'					=> 'filled',
			'about_main'				=> 'filled',
			'about_short'				=> 'filled',
			'about_our_aim'				=> 'filled',
			'about_who'					=> 'filled',
			'currency'					=> 'in:GBP,USD,EUR',
			'participant_count_offset'	=> 'numeric',
			'lan_count_offset'			=> 'numeric',
		];
		$messages = [
			'terms_and_conditions.filled'		=> 'Terms And Conditions cannot be empty',
			'org_name.filled'					=> 'Org Name cannot be empty',
			'about_main.filled'					=> 'About Main cannot be empty',
			'about_short.filled'				=> 'About Short cannot be empty',
			'about_our_aim.filled'				=> 'About Our Aim cannot be empty',
			'about_who.filled'					=> 'About Whos who cannot be empty',
			'currency.in'						=> 'Currency must be GBP, USD or EUR',
			'participant_count_offset.numeric'	=> 'Participant Count Offset must be a number',
			'lan_count_offset.numeric'			=> 'Lan Count Offset must be a number',
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

		if (isset($request->purchase_terms_and_conditions) && !Settings::setPurchaseTermsAndConditions($request->purchase_terms_and_conditions)) {
			Session::flash('alert-danger', 'Could not update!');
			return Redirect::back();
		}

		if (isset($request->registration_terms_and_conditions) && !Settings::setRegistrationTermsAndConditions($request->registration_terms_and_conditions)) {
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

	public function linkSocial($social, Facebook $facebook)
	{
		// DEBUG
		// dd($social);
		if (config('facebook.config.app_id') == null || config('facebook.config.app_secret') == null) {
			Session::flash('alert-danger', 'Facebook App is not configured');
			return Redirect::to('/admin/settings');
		}
		$accepted_social = array(
			'facebook',
			// 'twitter',
			// 'instagram',
		);
		if (!in_array($social, $accepted_social)) {
			Session::flash('alert-danger', "{$social} is not supported by the Lan Manager");
			return Redirect::to('/admin/settings');
		}

		if ($social == 'facebook') {
			$facebook_helper = $facebook->getRedirectLoginHelper();

			try {
			  	$access_token = $facebook_helper->getAccessToken();
			} catch(Facebook\Exceptions\FacebookResponseException $e) {
				// When Graph returns an error
				Session::flash('alert-danger', 'Graph returned an error: ' . $e->getMessage());
				return Redirect::to('/admin/settings');
			} catch(Facebook\Exceptions\FacebookSDKException $e) {
			  	// When validation fails or other local issues
				Session::flash('alert-danger', 'Facebook SDK returned an error: ' . $e->getMessage());
				return Redirect::to('/admin/settings');
			}
			if (! isset($access_token)) {
				if ($facebook_helper->getError()) {
					header('HTTP/1.0 401 Unauthorized');
					$message = "Error: " . $facebook_helper->getError() . "\n";
					$message .= "Error Code: " . $facebook_helper->getErrorCode() . "\n";
					$message .= "Error Reason: " . $facebook_helper->getErrorReason() . "\n";
					$message .= "Error Description: " . $facebook_helper->getErrorDescription() . "\n";
					Session::flash('alert-danger', 'HTTP/1.0 401 Unauthorized.' . "\n" . $message);
					return Redirect::to('/admin/settings');
				}
				Session::flash('alert-danger', 'HTTP/1.0 400 Bad Request.');
				return Redirect::to('/admin/settings');
			}

			// The OAuth 2.0 client handler helps us manage access tokens
			$oauth_client = $facebook->getOAuth2Client();

			// Get the access token metadata from /debug_token
			$token_metadata = $oauth_client->debugToken($access_token);
			$token_metadata->validateAppId(config('facebook.config.app_id'));
			$token_metadata->validateExpiration();

			if (!$access_token->isLongLived()) {
				// Exchanges a short-lived access token for a long-lived one
				try {
					$access_token = $oauth_client->getLongLivedAccessToken($access_token);
				} catch (Facebook\Exceptions\FacebookSDKException $e) {
					Session::flash('alert-danger', "Error getting long-lived access token: " . $e->getMessage());
					return Redirect::to('/admin/settings');
				}
			}

			// DEBUG - test this isn't needed
			// $_SESSION['fb_access_token'] = (string) $access_token;

			try{
				$response = ($facebook->get('/me/accounts?fields=access_token', $access_token))->getDecodedBody();
			} catch (Facebook\Exceptions\FacebookSDKException $e) { 
				Session::flash('alert-danger', "Error getting long-lived access token: " . $e->getMessage());
				return Redirect::to('/admin/settings');
			}
			$facebook_access_tokens = array();
			foreach ($response['data'] as $pages) {
				array_push($facebook_access_tokens, $pages['access_token']);
			}

			if (!Settings::setSocialFacebookPageAccessTokens($facebook_access_tokens)) {
				Session::flash('alert-danger', "Could not Link {$social}!");
				return Redirect::to('/admin/settings');
			}
		}

		Session::flash('alert-success', "Successfully Linked {$social}!");
		return Redirect::to('/admin/settings');
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
