<?php

namespace App\Http\Controllers\Admin;

use DB;
use Auth;
use Session;
use Redirect;
use Settings;
use Input;
use FacebookPageWrapper as Facebook;

use App\User;
use App\SliderImage;
use App\Setting;
use App\Event;
use App\EventParticipant;
use App\EventTicket;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Leafo\ScssPhp\Compiler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    /**
     * Show Settings Index Page
     * @return Redirect
     */
    public function index()
    {
        
        $facebookCallback = null;
        if (Facebook::isEnabled() && !Facebook::isLinked()) {
            $facebookCallback = Facebook::getLoginUrl();
        }
        return view('admin.settings.index')
            ->withSettings(Setting::all())
            ->withFacebookCallback($facebookCallback)
            ->withSupportedPaymentGateways(Settings::getSupportedPaymentGateways())
            ->withActivePaymentGateways(Settings::getPaymentGateways())
            ->withIsCreditEnabled(Settings::isCreditEnabled())
            ->withIsShopEnabled(Settings::isShopEnabled())
            ->withSliderImages(SliderImage::getImages('frontpage'))
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
            'terms_and_conditions.filled'       => 'Terms And Conditions cannot be empty',
            'org_name.filled'                   => 'Org Name cannot be empty',
            'about_main.filled'                 => 'About Main cannot be empty',
            'about_short.filled'                => 'About Short cannot be empty',
            'about_our_aim.filled'              => 'About Our Aim cannot be empty',
            'about_who.filled'                  => 'About Whos who cannot be empty',
            'currency.in'                       => 'Currency must be GBP, USD or EUR',
            'participant_count_offset.numeric'  => 'Participant Count Offset must be a number',
            'lan_count_offset.numeric'          => 'Lan Count Offset must be a number',
        ];
        $this->validate($request, $rules, $messages);

        if (isset($request->steam_link) && !Settings::setSteamLink($request->steam_link)) {
            Session::flash('alert-danger', 'Could not update!');
            return Redirect::back();
        }

        if (isset($request->teamspeak_link) && !Settings::setTeamspeakLink($request->teamspeak_link)) {
            Session::flash('alert-danger', 'Could not update!');
            return Redirect::back();
        }

        if (isset($request->discord_link) && !Settings::setDiscordLink($request->discord_link)) {
            Session::flash('alert-danger', 'Could not update!');
            return Redirect::back();
        }

        if (isset($request->discord_id) && !Settings::setDiscordId($request->discord_id)) {
            Session::flash('alert-danger', 'Could not update!');
            return Redirect::back();
        }

        if (isset($request->reddit_link) && !Settings::setRedditLink($request->reddit_link)) {
            Session::flash('alert-danger', 'Could not update!');
            return Redirect::back();
        }

        if (isset($request->facebook_link) && !Settings::setFacebookLink($request->facebook_link)) {
            Session::flash('alert-danger', 'Could not update!');
            return Redirect::back();
        }
        
        if (isset($request->participant_count_offset) &&
            !Settings::setParticipantCountOffset($request->participant_count_offset)
        ) {
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

        if (isset($request->purchase_terms_and_conditions) &&
            !Settings::setPurchaseTermsAndConditions($request->purchase_terms_and_conditions)
        ) {
            Session::flash('alert-danger', 'Could not update!');
            return Redirect::back();
        }

        if (isset($request->registration_terms_and_conditions) &&
            !Settings::setRegistrationTermsAndConditions($request->registration_terms_and_conditions)
        ) {
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
     * Link Social Platform for posting Images & News
     * @param  String $social
     * @return Redirect
     */
    public function linkSocial($social)
    {
        if ($social == 'facebook' && (!Facebook::isEnabled())) {
            Session::flash('alert-danger', 'Facebook App is not configured.');
            return Redirect::to('/admin/settings');
        }
        if ($social == 'facebook' && (Facebook::isLinked())) {
            Session::flash('alert-danger', 'Facebook is already Linked.');
            return Redirect::to('/admin/settings');
        }
        $acceptedSocial = array(
            'facebook',
            // 'twitter',
            // 'instagram',
        );
        if (!in_array($social, $acceptedSocial)) {
            Session::flash('alert-danger', "{$social} is not supported by the Lan Manager.");
            return Redirect::to('/admin/settings');
        }

        if ($social == 'facebook' && (Facebook::isEnabled() && !Facebook::isLinked())) {
            if (!$userAccessToken = Facebook::getUserAccessToken()) {
                Session::flash('alert-danger', 'Facebook: 401 Unauthorized Request.');
                return Redirect::to('/admin/settings');
            }
            if (!$pageAccessToken = Facebook::getPageAccessTokens($userAccessToken)) {
                Session::flash('alert-danger', "Facebook: Error getting long-lived access token");
                return Redirect::to('/admin/settings');
            }
            if (!Settings::setSocialFacebookPageAccessTokens($pageAccessToken)) {
                Session::flash('alert-danger', "Could not Link {$social}!");
                return Redirect::to('/admin/settings');
            }
        }
        Session::flash('alert-success', "Successfully Linked {$social}!");
        return Redirect::to('/admin/settings');
    }

    /**
     * Update Slider Image
     * @param  Request $request
     * @return Redirect
     */
    public function updateSliderImage(Request $request)
    {
        dd($request);
    }

    /**
     * Unlink Social Platform
     * @param  String $social
     * @return Redirect
     */
    public function unlinkSocial($social)
    {
        if (!Settings::setSocialFacebookPageAccessTokens(null)) {
            Session::flash('alert-danger', "Could not Unlink {$social}!");
            return Redirect::to('/admin/settings');
        }
        Session::flash(
            'alert-success',
            "Successfully Uninked {$social}. You will still need to remove the app access on Facebook!"
        );
        return Redirect::to('/admin/settings');
    }

    /**
     * Enable Payment Gateway
     * @param  String $gateway
     * @return Redirect
     */
    public function enablePaymentGateway($gateway)
    {
        if (!Settings::enablePaymentGateway($gateway)) {
            Session::flash('alert-danger', "Could not Enable {$gateway}!");
            return Redirect::to('/admin/settings');
        }
        Session::flash('alert-success', "Successfully Enabled {$gateway}!");
        return Redirect::to('/admin/settings');
    }

    /**
     * Disable Payment Gateway
     * @param  String $gateway
     * @return Redirect
     */
    public function disablePaymentGateway($gateway)
    {
        if (!Settings::disablePaymentGateway($gateway)) {
            Session::flash('alert-danger', "Could not Disable {$gateway}!");
            return Redirect::to('/admin/settings');
        }
        Session::flash('alert-success', "Successfully Disabled {$gateway}!");
        return Redirect::to('/admin/settings');
    }
    
    /**
     * Enable Credit System
     * @return Redirect
     */
    public function enableCreditSystem()
    {
        if (!Settings::enableCreditSystem()) {
            Session::flash('alert-danger', "Could not Enable the Credit System!");
            return Redirect::to('/admin/settings');
        }
        Session::flash('alert-success', "Successfully Enabled the Credit System!");
        return Redirect::to('/admin/settings');
    }

    /**
     * Disable Credit System
     * @return Redirect
     */
    public function disableCreditSystem()
    {
        if (!Settings::disableCreditSystem()) {
            Session::flash('alert-danger', "Could not Disable the Credit System!");
            return Redirect::to('/admin/settings');
        }
        Session::flash('alert-success', "Successfully Disabled the Credit System!");
        return Redirect::to('/admin/settings');
    }
    
    /**
     * Enable Shop System
     * @return Redirect
     */
    public function enableShopSystem()
    {
        if (!Settings::enableShopSystem()) {
            Session::flash('alert-danger', "Could not Enable the Shop System!");
            return Redirect::to('/admin/settings');
        }
        Session::flash('alert-success', "Successfully Enabled the Shop System!");
        return Redirect::to('/admin/settings');
    }

    /**
     * Disable Shop System
     * @return Redirect
     */
    public function disableShopSystem()
    {
        if (!Settings::disableShopSystem()) {
            Session::flash('alert-danger', "Could not Disable the Shop System!");
            return Redirect::to('/admin/settings');
        }
        Session::flash('alert-success', "Successfully Disabled the Shop System!");
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
