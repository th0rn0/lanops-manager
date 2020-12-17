<?php

namespace App\Http\Controllers\Admin;

use DB;
use Auth;
use Storage;
use Image;
use Validator;
use Session;
use Settings;
use Colors;
use File;
use Mail;

use App\User;
use App\Event;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Mail\EventulaMailingMail;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

use Spatie\MailTemplates\TemplateMailable;
use Spatie\MailTemplates\Models\MailTemplate;


class MailingController extends Controller
{
    /**
     * Show Help Index Page
     * @return view
     */
    public function index()
    {
        $user = Auth::user();
        $userswithmail = User::whereNotNull('email')->get();
        $selectallusers = array();

        foreach($userswithmail as $user) {
            $selectallusers[$user->id] = $user->username;
        }
        return view('admin.mailing.index')->withMailTemplates(MailTemplate::all())
        ->withMailVariables(EventulaMailingMail::getVariables())
        ->withUsersWithMail($selectallusers)
        ->withUser($user);
    }

    /**
     * Show Help Page
     * @param MailTemplate $mailTemplate
     * @return view
     */
    public function show(MailTemplate $mailTemplate)
    {
        return view('admin.mailing.show')
            ->withMailTemplate($mailTemplate)
            ->withMailVariables(EventulaMailingMail::getVariables())
        ;
    }

    /**
     * Store Mailtemplate to DB
     * @param  Request $request
     * @return Redirect
     */
    public function store(Request $request)
    {
        $rules = [
            'subject'          => 'required',
        ];
        $messages = [
            'subject.required'         => 'subject is required',
        ];
        $this->validate($request, $rules, $messages);

        if (!$mailTemplate=MailTemplate::create([
            'mailable' => EventulaMailingMail::class,
            'subject' => $request->subject, 
            'html_template' => $request->html_template, 
            'text_template' => $request->text_template, 
        ]))
        {
            Session::flash('alert-danger', 'Cannot save Mailtemplate!');
            return Redirect::back();
        }

        Session::flash('alert-success', 'Successfully saved Mailtemplate!');
        return Redirect::to('admin/mailing/' . $mailTemplate->id);
    }

    /**
     * Update Mailtemplate
     * @param  MailTemplate     $mailTemplate
     * @param  Request                $request
     * @return Redirect
     */
    public function update(MailTemplate $mailTemplate, Request $request)
    {
        $rules = [
            'subject'          => 'required',
        ];
        $messages = [
            'subject.required'         => 'subject is required',
        ];
        $this->validate($request, $rules, $messages);

        $mailTemplate->subject = $request->subject;
        $mailTemplate->html_template = $request->html_template;
        $mailTemplate->text_template = $request->text_template;


        if (!$mailTemplate->save()) {
            Session::flash('alert-danger', 'Cannot update Mailtemplate!');
            return Redirect::back();
        }

        Session::flash('alert-success', 'Successfully updated Mailtemplate!');
        return Redirect::back();
    }

    /**
     * Delete MailTemplate
     * @param  MailTemplate $mailTemplate
     * @return Redirect
     */
    public function destroy(MailTemplate $mailTemplate)
    {
        if (!$mailTemplate->delete()) {
            Session::flash('alert-danger', 'Cannot delete Mailtemplate!');
            return Redirect::back();
        }

        Session::flash('alert-success', 'Successfully deleted Mailtemplate!');
        return Redirect::to('admin/mailing/');
    }


    /**
     * sends MailTemplate
     * @param  MailTemplate $mailTemplate
     * @return Redirect
     */
    public function send(MailTemplate $mailTemplate, Request $request)
    {
        $requestvarname = "userswithmails".$mailTemplate->id;
        $selectedusers = $request->{$requestvarname};

        if (count($selectedusers) == 0)
        {
            Session::flash('alert-danger', 'no Users selected!');
            return Redirect::back();
        }

        $erroruser = array();

        foreach ($selectedusers as $selecteduser)
        {
            $user = User::whereNotNull('email')->where('id', $selecteduser)->first();

            dd(Mail::to($user)->queue(new EventulaMailingMail($user)));
            if(!isset($user))
            {
                $erroruser[$user->id] = $user->username;
            }

        }

        if (count($erroruser) != 0)
        {
            $erroruserprint = "";
            foreach ($erroruser as $euser)
            {
                $erroruserprint=$erroruserprint.";".$euser;
            }
            Session::flash('alert-danger', 'Could not queue Mails for the users: '. $erroruserprint);
            return Redirect::back();
        }


        Session::flash('alert-success', 'Successfully deleted Mailtemplate!');
        return Redirect::to('admin/mailing/');
    }

    
}
