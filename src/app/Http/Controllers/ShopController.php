<?php

namespace App\Http\Controllers;

use DB;
use Auth;

use App\ShopItem;
use App\ShopItemCategory;

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
        return view('shop.cart')
            ->withAllCategories(ShopItemCategory::all()->reverse())
        ;
    }

    /**
     * Update Cart
     * @return View
     */
    public function updateCart(Request $request)
    {
        $rules = [
            'item_id'   => 'required|exists:shop_item,id',
            'quantity'  => 'number',
        ];
        $messages = [
            'item_id.required'  => 'Item is Required.',
            'item_id.exists'    => 'Item does not exist.',
            'quantity.number'   => 'Quantity must be a number.',
        ];
        $this->validate($request, $rules, $messages);
        return view('shop.cart')
            ->withAllCategories(ShopItemCategory::all()->reverse())
        ;
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
