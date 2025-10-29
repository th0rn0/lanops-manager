<?php

namespace App\Http\Controllers\Admin\Events;

use Session;

use App\Models\Event;
use App\Models\EventInformation;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

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
            'text'  => 'required',
            'image' => 'image',
        ];
        $messages = [
            'title.required'    => 'A Title is required',
            'text.required'     => 'Some Information is required',
            'image.image'       => 'The file must be a Image',
        ];
        $this->validate($request, $rules, $messages);

        $information            = new EventInformation();
        $information->title     = $request->title;
        $information->text      = $request->text;
        $information->event_id  = $event->id;

        if (!$information->save()) {
            Session::flash('alert-danger', 'Cannot save Event Information!');
            return Redirect::back();
        }

        if ($request->file('image') !== null && !$information->addMediaFromRequest('image')->toMediaCollection()) {
            Session::flash('alert-danger', 'Cannot save Event Information Image!');
            return Redirect::back();
        }

        Session::flash('alert-success', 'Successfully saved Event Information!');
        return Redirect::to('admin/events/' . $event->slug);
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
            'title' => 'filled',
            'text'  => 'filled',
            'order' => 'integer',
        ];
        $messages = [
            'image.image'   => 'The file must be a Image',
            'title.filled'  => 'Title cannot be blank',
            'text.filled'   => 'Text cannot be blank',
            'order.integer' => 'Order must be a number',
        ];
        $this->validate($request, $rules, $messages);

        if (isset($request->title)) {
            $information->title = $request->title;
        }

        if (isset($request->text)) {
            $information->text  = $request->text;
        }

        if (isset($request->order)) {
            $information->order = $request->order;
        }
        
        if (!$information->save()) {
            Session::flash('alert-danger', 'Cannot update Event Information!');
            return Redirect::back();
        }

        if ($request->file('image') !== null) {
            if ($information->hasMedia()) {
                $information->clearMediaCollection();
            }
            if (!$information->addMediaFromRequest('image')->toMediaCollection()) {
                Session::flash('alert-danger', 'Cannot save Event Information Image!');
                return Redirect::back();
            }
        }

        Session::flash('alert-success', 'Successfully updated Event Information!');
        return Redirect::to('admin/events/' . $information->event->slug);
    }

    /**
     * Delete Information from the Database
     * @param  EventInformation $information
     * @return Redirect
     */
    public function destroy(EventInformation $information)
    {
        $information->clearMediaCollection();

        if (!$information->delete()) {
            Session::flash('alert-danger', 'Cannot delete Event Information!');
            return Redirect::back();
        }

        session::flash('alert-success', 'Successfully deleted Event Information!');
        return Redirect::to('admin/events/' . $information->event->slug);
    }
}
