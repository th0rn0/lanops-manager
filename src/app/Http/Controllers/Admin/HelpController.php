<?php

namespace App\Http\Controllers\Admin;

use DB;
use Auth;
use Storage;
use Image;
use Validator;
use Session;
use File;

use App\User;
use App\Event;
use App\HelpCategory;
use App\HelpCategoryEntry;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;

class HelpController extends Controller
{
    /**
     * Show Help Index Page
     * @return view
     */
    public function index()
    {
        return view('admin.help.index')
            ->withHelpCategorys(HelpCategory::paginate(20))
        ;
    }
    
    /**
     * Show Help Page
     * @return view
     */
    public function show(HelpCategory $helpCategory)
    {
        return view('admin.help.show')
            ->withHelpCategory($helpCategory)
            ->withEntrys($helpCategory->entrys()->paginate(10))
        ;
    }
    
    /**
     * Store Helpcategory to DB
     * @param  Request $request
     * @return Redirect
     */
    public function store(Request $request)
    {
        $rules = [
            'name'          => 'required',
            'description'   => 'required'
        ];
        $messages = [
            'name.required'         => 'Name is required',
            'description.required'  => 'Description is required'
        ];
        $this->validate($request, $rules, $messages);

        $helpCategory              = new HelpCategory();
        $helpCategory->name        = $request->name;
        $helpCategory->description = $request->description;

        if (!$helpCategory->save()) {
            Session::flash('alert-danger', 'Cannot save HelpCategory!');
            return Redirect::to('admin/help');
        }

        Session::flash('alert-success', 'Successfully saved HelpCategory!');
        return Redirect::to('admin/help/' . $helpCategory->slug);
    }
    
    /**
     * Update Helpcategory
     * @param  HelpCategory           $helpCategory
     * @param  HelpCategoryEntry|null $entry
     * @param  Request                $request
     * @return Redirect
     */
    public function update(HelpCategory $helpCategory, HelpCategoryEntry $entry = null, Request $request)
    {
        $rules = [
            'name'          => 'filled',
            'description'   => 'filled',
            'status'        => 'in:draft,published',
        ];
        $messages = [
            'name.filled'           => 'Name cannot be empty',
            'description.filled'    => 'Description cannot be empty',
            'status.in'             => 'Status must be draft or published',
        ];
        $this->validate($request, $rules, $messages);

        if (isset($request->name)) {
            $helpCategory->name        = $request->name;
        }

        if (isset($request->description)) {
            $helpCategory->description = $request->description;
        }

        if (isset($request->status)) {
            $helpCategory->status      = $request->status;
        }

        if (isset($request->event_id)) {
            $helpCategory->event_id    = $request->event_id;
        }

        if (!$helpCategory->save()) {
            Session::flash('alert-danger', 'Cannot update HelpCategory!');
            return Redirect::back();
        }

        Session::flash('alert-success', 'Successfully updated HelpCategory!');
        return Redirect::back();
    }

    /**
     * Delete HelpCategory
     * @param  HelpCategory $helpCategory
     * @return Redirect
     */
    public function destroy(HelpCategory $helpCategory)
    {
        if (!$helpCategory->delete()) {
            Session::flash('alert-danger', 'Cannot delete Gallery!');
            return Redirect::back();
        }

        Session::flash('alert-success', 'Successfully deleted Gallery!');
        return Redirect::back();
    }

    /**
     * Create Helpentry
     * @param  HelpCategory $helpCategory
     * @param  Request      $request
     * @return Redirect
     */
    public function createHelpEntry(HelpCategory $helpCategory, Request $request)
    {
        $rules = [
            'name'          => 'filled',
            'content'   => 'filled'
        ];
        $messages = [
            'name.filled'           => 'Name cannot be empty',
            'content.filled'    => 'Description cannot be empty'
        ];
        $this->validate($request, $rules, $messages);
            $entry                          = new HelpCategoryEntry();
            $entry->display_name            = $request->name;
            $entry->content                 = $request->content;
            $entry->nice_name               = strtolower(str_replace(' ', '-', $request->name));
            $entry->help_category_id        = $helpCategory->id;
            
        if (!$entry->save()) {
            Session::flash('alert-danger', 'creation of entry unsuccessful!');
            return Redirect::to('admin/help/' . $helpCategory->slug);
        }

        Session::flash('alert-success', 'creation of entry successful!');
        return Redirect::to('admin/help/' . $helpCategory->slug);
    }

    /**
     * Delete entry from HelpCategory
     * @param  HelpCategory      $helpCategory
     * @param  HelpCategoryEntry $entry
     * @return Redirect
     */
    public function destroyHelpEntry(HelpCategory $helpCategory, HelpCategoryEntry $entry)
    {
        if (!$entry->delete()) {
            Session::flash('alert-danger', 'Cannot delete entry!');
            return Redirect::back();
        }

        Session::flash('alert-success', 'Successfully deleted entry!');
        return Redirect::back();
    }

    /**
     * Update entry from HelpCategory
     * @param  HelpCategory      $helpCategory
     * @param  HelpCategoryEntry $entry
     * @param  Request           $request
     * @return Redirect
     */
    public function updateHelpEntry(HelpCategory $helpCategory, HelpCategoryEntry $entry, Request $request)
    {
        //DEBUG - Refactor - replace iamge name as well!
        $entry->display_name  = $request->name;
        $entry->nice_name     = strtolower(str_replace(' ', '-', $request->name));
        $entry->content          = $request->content;

        if (!$entry->save()) {
            Session::flash('alert-danger', 'Could not update entry!');
            return Redirect::back();
        }

        Session::flash('alert-success', 'Successfully updated entry!');
        return Redirect::back();
    }
}
