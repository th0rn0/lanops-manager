<?php

namespace App\Models;

use Auth;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

use Cviebrock\EloquentSluggable\Sluggable;

use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class GalleryAlbum extends Model implements HasMedia
{
    use Sluggable;
    use InteractsWithMedia;

    /**
     * The name of the table.
     *
     * @var string
     */
    protected $table = 'gallery_albums';


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
    // public function images()
    // {
    //     return $this->hasMany('App\Models\GalleryAlbumImage');
    // }

    public function event()
    {
        return $this->belongsTo('App\Models\Event', 'event_id');
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
     * Set Album Cover
     * @param $imageId
     */
    public function setAlbumCover($imageId)
    {
        $this->album_cover_id = $imageId;
        $this->save();
    }

    /**
     * Get Album Cover Path
     * @return String
     */
    public function getAlbumCoverPath()
    {
        dd('fixme');
        return $this->images()->where('id', $this->album_cover_id)->first()->path;
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this
            ->addMediaConversion('preview')
            ->fit(Manipulations::FIT_CROP, 300, 300)
            ->nonQueued();
    }
}
