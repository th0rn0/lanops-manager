<?php

namespace App\Http\Controllers;

use DB;
use Auth;

use App\Http\Requests;

use Illuminate\Http\Request;

class AccountController extends Controller
{
    /**
     * Show Account Index Page
     * @return View
     */
    public function index()
    {
        $user = Auth::user();
        return view("accounts.index")
            ->withUser($user);
    }
}
