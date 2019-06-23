<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShopItemCategory extends Model
{
    /**
     * The name of the table.
     *
     * @var string
     */
    protected $table = 'shop_item_categories';

    protected $fillable = [
        'name',
        'order'
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
    public function category()
    {
        return $this->hasMany('App\ShopItem', 'shop_item_category_id');
    }

}
