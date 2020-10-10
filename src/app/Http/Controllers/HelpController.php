<?php

namespace App\Http\Controllers;

use DB;
use Auth;

use App\Event;
use App\HelpCategory;
use App\HelpCategoryEntry;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class HelpController extends Controller
{
    /**
     * Show Help Index Page
     * @return View
     */
    public function index()
    {
        $event = Event::where('start', '>=', date("Y-m-d 00:00:00"))->first();
        $helpCategorys = HelpCategory::all();
        return view('help.index')
            ->withHelpCategorys($helpCategorys)
            ->withEvent($event);
    }

    /**
     * Show Help Category Page
     * @param  HelpCategory $helpCategory
     * @return View
     */
    public function show(HelpCategory $helpCategory)
    {
        return view('help.show')
            ->withHelpCategory($helpCategory);
    }


}
