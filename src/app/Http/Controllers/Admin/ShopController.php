<?php

namespace App\Http\Controllers\Admin;

use DB;
use Auth;
use Session;
use Settings;

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
        return view('admin.shop.index')
            ->withIsShopEnabled(Settings::isShopEnabled())
            ->withCategories(ShopItemCategory::all())
            ->withItems(ShopItem::all())
        ;
    }

    /**
     * Show Shop Category Page
     * @return View
     */
    public function showCategory(ShopItemCategory $category)
    {
        return view('admin.shop.category')
            ->withIsShopEnabled(Settings::isShopEnabled())
            ->withCategory($category)
        ;
    }

    /**
     * Show Shop Item Page
     * @return View
     */
    public function showItem(ShopItemCategory $category, ShopItem $item)
    {
        return view('admin.shop.item')
            ->withIsShopEnabled(Settings::isShopEnabled())
            ->withCategory($category)
            ->withItem($item)
        ;
    }

 	/**
     * Store Shop Category
     * @param $request
     * @return View
     */
    public function storeCategory(Request $request)
    {
    	$rules = [
    		'name' => 'required|unique:shop_item_categories,name',
    	];
    	$messages = [
    		'name.required' => 'Category Name is Required.',
    		'name.unique' => 'Category Name must be Unique.'
    	];
    	$this->validate($request, $rules, $messages);

	  	if (!ShopItemCategory::create(['name' => $request->name])) {
  		 	Session::flash('alert-danger', 'Cannot create Category!');
            return Redirect::to('admin/shop/');
	  	}
        Session::flash('alert-success', 'Successfully created Category!');
        return Redirect::to('admin/shop/');
    }

    /**
     * Store Shop Item
     * @param $request
     * @return View
     */
    public function storeItem(Request $request)
    {
    	$rules = [
    		'name' 			=> 'required',
    		'quantity' 		=> 'integer',
    		'category_id' 	=> 'required|exists:shop_item_categories,id',
    		'price_real' 	=> 'integer',
    		'price_credit' 	=> 'integer'
    	];
    	$messages = [
    		'name.required' 		=> 'Item Name is Required.',
    		'quantity.integer' 		=> 'Quantity must be a number.',
    		'category_id.required' 	=> 'A Category is required.',
    		'category_id.exists'    => 'A Category must exist.',
    		'price_real.integer' 	=> 'Real Price must be a number.',
    		'price_credit.integer' 	=> 'Credit Price must be a number.',
    	];
    	$this->validate($request, $rules, $messages);

    	$params = [
    		'name'					=> $request->name,
    		'quantity'				=> $request->quantity,
    		'shop_item_category_id'	=> $request->category_id,
    		'price_real'			=> $request->price_real,
    		'price_credit' 			=> $request->price_credit,
    		'added_by' 				=> Auth::id(),
    	];
	  	if (!ShopItem::create($params)) {
  		 	Session::flash('alert-danger', 'Cannot save Item!');
            return Redirect::to('admin/shop/');
	  	}
        Session::flash('alert-success', 'Successfully saved Item!');
        return Redirect::to('admin/shop/');
    }
}
