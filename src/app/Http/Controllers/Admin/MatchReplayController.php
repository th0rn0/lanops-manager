<?php

namespace App\Http\Controllers\Admin;

use DB;
use Auth;
use Session;
use Storage;
use Image;
use File;

use App\MatchReplay;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class MatchReplayController extends Controller
{
    /**
     * Delete MatchReplay from Database
     * @param  MatchReplay $matchReplay
     * @return Redirect
     */
    public function destroy(MatchReplay $matchReplay)
    {
        if (!$matchReplay->delete()) {
            Session::flash('alert-danger', 'Cannot delete matchReplay!');
            return Redirect::back();
        }

        Session::flash('alert-success', 'Successfully deleted matchReplay!');
        return Redirect::back();
    }
}
