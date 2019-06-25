<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Cviebrock\EloquentSluggable\Sluggable;

class ShopItemImage extends Model
{
    /**
     * The name of the table.
     *
     * @var string
     */
    protected $table = 'shop_item_images';

    protected $fillable = [
        'path',
        'default',
        'shop_item_id',
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
    public function item()
    {
        return $this->belongsTo('App\ShopItem', 'shop_item_id');
    }
}
