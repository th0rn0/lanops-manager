<?php

namespace App\Http\Controllers\Admin;

use DB;
use Auth;
use Session;
use Settings;

use App\ShopItem;
use App\ShopItemImage;
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
            ->withCategories(ShopItemCategory::paginate(10, ['*'], 'sc'))
            ->withItems(ShopItem::paginate(20, ['*'], 'it'));
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
            ->withItems($category->items()->paginate());
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
            ->withItem($item);
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
     * Update Shop Category
     * @param ShopItemCategory $category
     * @param $request
     * @return View
     */
    public function updateCategory(ShopItemCategory $category, Request $request)
    {
        $rules = [
            'name'      => 'filled',
            'order'     => 'integer',
            'status'    => 'in:draft,published,hidden',
        ];
        $messages = [
            'name.filled'   => 'Category Name cannot be blank.',
            'name.unique'   => 'Category Name must be Unique.',
            'order.integer' => 'Order must be a number',
            'status.in'     => 'Status must be Draft, Publushed or Hidden',
        ];
        $this->validate($request, $rules, $messages);

        $category->name = @$request->name;
        $category->order = @$request->order;
        $category->status = @strtoupper($request->status);

        if (!$category->save()) {
            Session::flash('alert-danger', 'Cannot update Category!');
            return Redirect::back();
        }
        Session::flash('alert-success', 'Successfully updated Category!');
        return Redirect::back();
    }

    /**
     * Store Shop Item
     * @param $request
     * @return View
     */
    public function storeItem(Request $request)
    {
    	$rules = [
    		'name'          => 'required',
    		'stock' 		=> 'integer',
    		'category_id'   => 'required|exists:shop_item_categories,id',
    		'price'         => 'integer',
    		'price_credit' 	=> 'integer',
    	];
    	$messages = [
    		'name.required' 	    => 'Item Name is Required.',
    		'stock.integer'         => 'Stock must be a number.',
    		'category_id.required' 	=> 'A Category is required.',
    		'category_id.exists'    => 'A Category must exist.',
    		'price.integer'         => 'Real Price must be a number.',
    		'price_credit.integer' 	=> 'Credit Price must be a number.',
    	];
    	$this->validate($request, $rules, $messages);

    	$params = [
    		'name'                    => $request->name,
    		'stock'                   => $request->stock,
    		'shop_item_category_id'	  => $request->category_id,
    		'price'                   => $request->price,
    		'price_credit'            => $request->price_credit,
    		'added_by'                => Auth::id(),
            'description'             => @$request->description,
    	];
	  	if (!$shopItem = ShopItem::create($params)) {
  		 	Session::flash('alert-danger', 'Cannot save Item!');
            return Redirect::to('admin/shop/');
	  	}
        $params = [
            'default' => true,
            'shop_item_id' => $shopItem->id,
        ];
        if (!ShopItemImage::create($params)) {
            $shopItem->destroy();
            Session::flash('alert-danger', 'Cannot save Item!');
            return Redirect::to('admin/shop/');
        }
        Session::flash('alert-success', 'Successfully saved Item!');
        return Redirect::to('admin/shop/');
    }

    /**
     * Update Shop Item
     * @param ShopItemCategory $category
     * @param ShopItem $item
     * @param $request
     * @return View
     */
    public function updateItem(ShopItemCategory $category, ShopItem $item, Request $request)
    {
        $rules = [
            'name'          => 'filled',
            'stock'         => 'integer',
            'category_id'   => 'required|exists:shop_item_categories,id',
            'price'         => 'integer',
            'price_credit'  => 'integer',
            'featured'      => 'boolean',
            'status'        => 'in:draft,published,hidden'
        ];
        $messages = [
            'name.filled'           => "Name cannot be empty",
            'stock.integer'         => 'Stock must be a number.',
            'category_id.required'  => 'A Category is required.',
            'category_id.exists'    => 'A Category must exist.',
            'price.integer'         => 'Real Price must be a number.',
            'price_credit.integer'  => 'Credit Price must be a number.',
            'featured.boolean'      => 'Featured must be a boolean.',
            'status.in'             => 'Status must be Draft, Published or Hidden.',
        ];
        $this->validate($request, $rules, $messages);

        $item->name = @$request->name;
        $item->stock = @$request->stock;
        $item->shop_item_category_id = @$request->category_id;
        $item->price = @$request->price;
        $item->price_credit = @$request->price_credit;
        $item->description = @$request->description;
        $item->featured = @$request->featured;
        $item->status = @strtoupper($request->status);
        if (!$item->save()) {
            Session::flash('alert-danger', 'Cannot update Item!');
            return Redirect::back();
        }
        Session::flash('alert-success', 'Successfully updated Item!');
        return Redirect::to('admin/shop/' . $category->slug . '/' . $item->slug);
    }
}
