<?php

namespace App\Http\Controllers\Admin;

use DB;
use Auth;
use Settings;
use Helpers;

use App\ShopOrder;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class OrdersController extends Controller
{
    /**
     * Show Order Index Page
     * @return view
     */
    public function index()
    {
        return view('admin.orders.index')->withOrders(ShopOrder::paginate(10));
    }

    /**
     * Show Order Page
     * @param  Order  $order
     * @return View
     */
    public function show(ShopOrder $order)
    {
        return view('admin.orders.show')
            ->withOrder($order);
    }
}
