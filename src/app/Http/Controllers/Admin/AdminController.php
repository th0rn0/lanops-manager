<?php

namespace App\Http\Controllers\Admin;

use DB;
use Auth;
use App\User;
use App\Event;
use App\ShopOrder;
use App\EventParticipant;
use App\EventTicket;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Show Admin Index Page
     * @return view
     */
    public function index()
    {
        $user = Auth::user();
        $events = Event::all();
        $orders = ShopOrder::getNewOrders('login');
        dd($orders);
        return view('admin.index')
            ->withUser($user)
            ->withEvents($events);
    }
}
