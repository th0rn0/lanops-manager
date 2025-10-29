<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

use Cviebrock\EloquentSluggable\Sluggable;

class EventVenue extends Model implements HasMedia
{
    use Sluggable;
    use InteractsWithMedia;

    /**
     * The name of the table.
     *
     * @var string
     */
    protected $table = 'event_venues';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'display_name',
        'address_1',
        'address_2',
        'address_2',
        'address_street',
        'address_city',
        'address_postcode',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [

    ];
    
    /*
     * Relationships
     */
    public function events()
    {
        return $this->hasMany('App\Models\Event');
    }

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'display_name'
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
}
