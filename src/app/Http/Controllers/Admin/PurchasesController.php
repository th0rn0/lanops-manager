<?php

namespace App\Http\Controllers\Admin;

use DB;
use Auth;
use Session;

use App\User;
use App\Purchase;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;

class PurchasesController extends Controller
{
    /**
     * Show All Purchases Index Page
     * @return View
     */
    public function index()
    {
        return view('admin.purchases.index')
            ->withPurchases(Purchase::paginate(20));
    }

    /**
     * Show Shop Purchases Index Page
     * @return View
     */
    public function showShop()
    {
        return view('admin.purchases.index')
            ->withPurchases(Purchase::has('order')->paginate(20));
    }

    /**
     * Show Event Purchases Index Page
     * @return View
     */
    public function showEvent()
    {
        return view('admin.purchases.index')
            ->withPurchases(Purchase::has('participants')->paginate(20));
    }

  	/**
     * Show Purchase Page
     * @param Purchase $purchase
     * @return View
     */
    public function show(Purchase $purchase)
    {
        return view('admin.purchases.show')
            ->withPurchase($purchase);
    }
}
