<?php

namespace App\Http\Controllers\Admin;

use DB;
use Auth;
use Session;
use Mail;

use App\User;
use App\Purchase;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Mail\EventulaTicketOrderPaymentFinishedMail;

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

    /**
     * Set Purchase Success
     * @param Purchase $purchase
     * @return View
     */
    public function setSuccess(Purchase $purchase)
    {
        if ($purchase->status != "Pending") {
            Session::flash('alert-danger', 'Purchase is not pending!');
            return Redirect::to('/admin/purchases/' . $purchase->id);
        }

        if (!$purchase->setSuccess()) {
            Session::flash('alert-danger', 'Cannot set purchase status!');
            return Redirect::to('/admin/purchases/' . $purchase->id);
        }

        Mail::to(Auth::user())->queue(new EventulaTicketOrderPaymentFinishedMail(Auth::user(), $purchase));

        Session::flash('alert-success', 'Successfully updated purchase status!');
        return Redirect::to('/admin/purchases/' . $purchase->id);
    }
}
