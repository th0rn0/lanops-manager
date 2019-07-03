<?php

namespace App;

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
        'total',
        'total_credit',
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

    /*
     * Relationships
     */
    public function items()
    {
        return $this->hasMany('App\ShopOrderItem', 'shop_order_id');
    }

    /**
     * Update Order
     * @param $itemId
     * @param $quantity
     * @return Boolean
     */
    public function updateOrder($itemId, $quantity)
    {
        $params = [
            'item_id'       => $itemId,
            'quantity'      => $quantity,
            'shop_order_id' => $this->id,
        ];
        if (!ShopOrderItem::create($params)) {
            return false;
        }
        return true;
    }
}
