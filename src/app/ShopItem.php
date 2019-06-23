<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShopItem extends Model
{
    /**
     * The name of the table.
     *
     * @var string
     */
    protected $table = 'shop_items';

    protected $fillable = [
        'name',
        'price_real',
        'price_credit',
        'shop_item_category_id',
        'quantity',
        'added_by',
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
    public function user()
    {
        return $this->belongsTo('App\User', 'added_by');
    }
    public function category()
    {
        return $this->belongsTo('App\ShopItemCategory', 'shop_item_category_id');
    }

}
