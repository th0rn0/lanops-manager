<?php

namespace App;

use DB;
use Auth;

use Illuminate\Database\Eloquent\Model;

use Cviebrock\EloquentSluggable\Sluggable;

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
        'price',
        'price_credit',
        'shop_item_category_id',
        'quantity',
        'status',
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

    protected static function boot()
    {
        parent::boot();

        $admin = false;
        if (Auth::user() && Auth::user()->getAdmin()) {
            $admin = true;
        }
        if (!$admin) {
            static::addGlobalScope('statusDraft', function (Builder $builder) {
                $builder->where('status', '!=', 'DRAFT');
            });
            static::addGlobalScope('statusPublished', function (Builder $builder) {
                $builder->where('status', 'PUBLISHED');
            });
        }
    }

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
    public function images()
    {
        return $this->hasMany('App\ShopItemImage', 'shop_item_id');
    }

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }
    
    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Get Default Image Url
     * @return Integer
     */
    public function getDefaultImageUrl()
    {
        if ($imagePath = 
            ShopItemImage::where('shop_item_id', $this->id)
                ->where('default', true)
                ->first()
            ) {
            return $imagePath->path;
        }
        return ShopItemImage::where('shop_item_id', $this->id)->path;
    }
}
