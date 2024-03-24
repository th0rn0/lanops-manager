<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GalleryAlbumImage extends Model
{
    /**
     * The name of the table.
     *
     * @var string
     */
    protected $table = 'gallery_album_images';

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
    public function album()
    {
        return $this->belongsTo('App\Models\GalleryAlbum');
    }
}
