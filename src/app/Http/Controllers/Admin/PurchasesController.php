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
     * Show Purchases Index Page
     * @return View
     */
    public function index()
    {
        return view('admin.purchases.index')
            ->withPurchases(Purchase::all());
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
