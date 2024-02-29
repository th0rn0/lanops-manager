<?php

namespace App\Http\Controllers\Events;

use Auth;
use Session;
use App\EventParticipant;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;

class ParticipantsController extends Controller
{
    /**
     * Gift Ticket
     * @param  EventParticipant $participant
     * @param  Request          $request
     * @return Redirect
     */
    public function gift(EventParticipant $participant, Request $request)
    {
        if ($participant->gift != true && $participant->gift_sendee == null) {
            $participant->gift = true;
            $participant->gift_accepted = false;
            $participant->gift_accepted_url = base_convert(microtime(false), 10, 36);
            $participant->gift_sendee = $participant->user_id;
            if ($participant->save()) {
                $request->session()->flash(
                    'alert-success',
                    'Ticket gifted Successfully! - Give your friend the URL below.'
                );
                return Redirect::back();
            }
            $request->session()->flash('alert-danger', 'Somthing went wrong. Please try again later.');
            return Redirect::back();
        }
        $request->session()->flash('alert-danger', 'This Ticket has already Gifted.');
        return Redirect::back();
    }
    
    /**
     * Revoke Gifted Ticket
     * @param  EventParticipant $participant
     * @param  boolean          $accepted
     * @return Redirect
     */
    public function revokeGift(EventParticipant $participant, $accepted = false)
    {
        if ($participant->gift == true) {
            if ($participant->gift_accepted != true) {
                if ($accepted !== true) {
                    $participant->gift = null;
                    $participant->gift_accepted = null;
                    $participant->gift_sendee = null;
                }
                $participant->gift_accepted_url = null;
                if ($participant->save()) {
                    Session::flash('alert-success', 'Ticket gift revoked Successfully!');
                    return Redirect::back();
                }
            }
        }
        Session::flash('alert-danger', 'This Ticket is already Gifted.');
        return Redirect::back();
    }
    
    /**
     * Accept Gifted Ticket
     * @param  Request $request
     * @return Redirect
     */
    public function acceptGift(Request $request)
    {
        $user = Auth::user();
        if ($user) {
            $participant = EventParticipant::where(['gift_accepted_url' => $request->url])->first();
            if ($participant != null) {
                $participant->gift_accepted = true;
                $participant->user_id = $user->id;
                $participant->gift_accepted_url = null;
                if ($participant->save()) {
                    $request->session()->flash(
                        'alert-success',
                        'Gift Successfully accepted! Please visit the event page to pick a seat'
                    );
                    return Redirect::to('account');
                }
                $request->session()->flash('alert-danger', 'Something went wrong. Please try again later.');
                return Redirect::to('account');
            }
            $request->session()->flash('alert-danger', 'Redemption code not found.');
            return Redirect::to('account');
        }
        $request->session()->flash('alert-danger', 'Please Login.');
        return Redirect::to('login');
    }
}
