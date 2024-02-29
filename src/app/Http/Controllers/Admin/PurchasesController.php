<?php

namespace App\Http\Controllers\Admin;

use App\Purchase;

use App\Http\Controllers\Controller;

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
