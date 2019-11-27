<?php

namespace App\Http\Controllers\Admin;

use DB;
use Auth;
use Settings;
use Helpers;
use Session;

use App\ShopOrder;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

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

    /**
    * Set Order as Shipped
    * @param  Request $request
    * @param  Order  $order
    * @return View
    */
    public function setAsShipped(Request $request, ShopOrder $order)
    {
        if (!$order->setAsShipped($request)) {
            Session::flash('alert-danger', 'Cannot mark as Shipped!');
            return Redirect::back();
        }
        Session::flash('alert-success', 'Successfully marked as Shipped!');
        return Redirect::back();
    }

    /**
    * Set Order as Complete
    * @param  Request $request
    * @param  Order  $order
    * @return View
    */
    public function setAsComplete(Request $request, ShopOrder $order)
    {
        if (!$order->setAsComplete($request)) {
            Session::flash('alert-danger', 'Cannot mark as Complete!');
            return Redirect::back();
        }
        Session::flash('alert-success', 'Successfully marked as Complete!');
        return Redirect::back();
    }

    /**
    * Set Order as Cancelled
    * @param  Request $request
    * @param  Order  $order
    * @return View
    */
    public function setAsCancelled(Request $request, ShopOrder $order)
    {
        if (!$order->setAsCancelled($request)) {
            Session::flash('alert-danger', 'Cannot Cancel order!');
            return Redirect::back();
        }
        Session::flash('alert-success', 'Successfully Cancelled order!');
        return Redirect::back();
    }
}
