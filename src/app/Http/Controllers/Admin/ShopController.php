<?php

namespace App\Http\Controllers\Admin;

use DB;
use Auth;
use Session;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    /**
     * Show Shop Index Page
     * @return View
     */
    public function index()
    {
        return view('admin.shop.index')
            ->withIsShopEnabled(Settings::isShopEnabled());
    }
}
