<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

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

    protected static function boot() {
        parent::boot();
        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderBy('order', 'asc');
        });
    }

    /*
     * Relationships
     */
    public function item()
    {
        return $this->belongsTo('App\ShopItem', 'shop_item_id');
    }
}
