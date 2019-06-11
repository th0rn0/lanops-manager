<?php

namespace App\Http\Controllers\Admin;

use DB;
use Auth;
use Settings;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;

class CreditController extends Controller
{
    /**
     * Show Credit System Index Page
     * @return View
     */
    public function index()
    {
        return view('admin.credit.index')
            ->withIsCreditEnabled(Settings::isCreditEnabled());
    }
}
