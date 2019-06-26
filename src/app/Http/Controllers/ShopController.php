<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use Settings;
use Helpers;

use App\ShopItem;
use App\ShopItemCategory;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ShopController extends Controller
{
    /**
     * Show Shop Index Page
     * @return View
     */
    public function index()
    {
        return view('shop.index')
            ->withAllCategories(ShopItemCategory::all()->reverse())
            ->withFeaturedItems(ShopItem::where('featured', true)->get())
        ;
    }

    /**
     * Show Cart Page
     * @return View
     */
    public function showCart()
    {
        if (Session::has(Settings::getOrgName() . '-cart')) {
            $cart = Helpers::formatCart(Session::get(Settings::getOrgName() . '-cart'));
        } else {
            $cart = 'Empty';
        }
        return view('shop.cart')
            ->withAllCategories(ShopItemCategory::all()->reverse())
            ->withCart($cart)
        ;
    }

    /**
     * Update Cart
     * @return View
     */
    public function updateCart(Request $request)
    {
        $rules = [
            'item_id'   => 'required|exists:shop_items,id',
            'quantity'  => 'integer',
        ];
        $messages = [
            'item_id.required'  => 'Item is Required.',
            'item_id.exists'    => 'Item does not exist.',
            'quantity.integer'   => 'Quantity must be a number.',
        ];
        $this->validate($request, $rules, $messages);
        if (!Helpers::stockCheck($request->item_id)) {
            Session::flash('alert-danger', 'Not enough in Stock. Please try again later.');
            return Redirect::back();
        }
        if ($params = Session::get(Settings::getOrgName() . 'cart')) {
            if (in_array($request->item_id, $params)) {
                $params[$request->item_id] += $request->quantity;
            } else {
                array_push($params, [$request->item_id => $request->quantity]);
            }
        } else {
            $params = [
                $request->item_id => $request->quantity,
            ];
        }
        Session::put(Settings::getOrgName() . '-cart', $params);
        Session::save();
        Session::flash('alert-success', 'Cart Updated!');
        return Redirect::to('/shop/cart');
    }

    /**
     * Show Orders Page
     * @return View
     */
    public function showOrders()
    {
        return view('shop.orders')
            ->withAllCategories(ShopItemCategory::all()->reverse())
        ;
    }

    /**
     * Show Checkout Page
     * @return View
     */
    public function showCheckout()
    {
        return view('shop.checkout')
            ->withAllCategories(ShopItemCategory::all()->reverse())
        ;
    }

    /**
     * Show Shop Category Page
     * @param ShopItemCategory $category
     * @return View
     */
    public function showCategory(ShopItemCategory $category)
    {
        return view('shop.category')
            ->withCategory($category)
            ->withAllCategories(ShopItemCategory::all()->reverse())
        ;
    }

    /**
     * Show Shop Item Page
     * @param ShopItem $item
     * @param ShopItemCategory $category
     * @return View
     */
    public function showItem(ShopItemCategory $category, ShopItem $item)
    {
        return view('shop.item')
            ->withCategory($category)
            ->withItem($item)
            ->withAllCategories(ShopItemCategory::all()->reverse())
        ;
    }

}
