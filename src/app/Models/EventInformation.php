<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class EventInformation extends Model implements HasMedia
{
    use InteractsWithMedia;

    /**
     * The name of the table.
     *
     * @var string
     */
    protected $table = 'event_information';
    
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
    public function event()
    {
        return $this->belongsTo('App\Models\Event');
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this
            ->addMediaConversion('optimized')
            ->fit(Manipulations::FIT_MAX, 1000, 1000)
            ->optimize()
            ->queued();
    }
}
