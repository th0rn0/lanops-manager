<?php

namespace App;

use Auth;

use App\ShopOrderItem;

use Illuminate\Database\Eloquent\Model;

use Cviebrock\EloquentSluggable\Sluggable;

class ShopOrder extends Model
{
    /**
     * The name of the table.
     *
     * @var string
     */
    protected $table = 'shop_orders';

    protected $fillable = [
        'purchase_id',
        'status',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = array(
        'created_at',
        'updated_at'
    );

    /*
     * Relationships
     */
    public function purchase()
    {
        return $this->belongsTo('App\Purchase', 'purchase_id');
    }
    public function items()
    {
        return $this->hasMany('App\ShopOrderItem', 'shop_order_id');
    }

    /**
     * Update Order
     * @param $item
     * @param $quantity
     * @return Boolean
     */
    public function updateOrder($item)
    {
        $params = [
            'shop_item_id'  => $item->id,
            'quantity'      => $item->quantity,
            'price'         => $item->price,
            'price_credit'  => $item->price_credit,
            'shop_order_id' => $this->id,
        ];
        if (!ShopOrderItem::create($params)) {
            return false;
        }
        return true;
    }

    /**
     * Get New Orders
     * @param $type
     * @return Orders
     */
    public static function getNewOrders($type = 'all')
    {
        if (!$user = Auth::user()) {
            $type = 'all';
        }
        switch ($type) {
            case 'login':
                $orders = self::where('created_at', '>=', $user->last_login)->get();
                break;
            default:
                $orders = self::where('created_at', '>=', date('now - 1 day'))->get();
                break;
        }
        return $orders;
    }
}
