<?php

namespace App\Http\Controllers\Admin\Events;

use App\Event;
use App\EventSponsor;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
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
        $sponsor            = new EventSponsor();
        $sponsor->event_id  = $event->id;
        $sponsor->name      = $request->sponsor_name;

        if ($request->file('sponsor_image') !== null) {
            $sponsor->image_path = str_replace(
                'public/',
                '/storage/',
                Storage::put(
                    'public/images/events/' . $event->slug . '/sponsors',
                    $request->file('sponsor_image')
                )
            );
        }

        if (!$sponsor->save()) {
            Session::flash('alert-danger', 'Could not save Sponsor!');
            return Redirect::back();
        }

        Session::flash('alert-success', 'Successfully Saved Sponsor!');
        return Redirect::to('admin/events/' . $event->slug);
    }
}
