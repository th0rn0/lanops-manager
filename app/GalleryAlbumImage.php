<?php

namespace App;

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
        return $this->belongsTo('App\GalleryAlbum');
    }

    public function store($request, $album)
    {
        $destination_path = 'uploads/gallery/' . $album->nice_name;
        if($request->file('image') !== NULL){
            $image_name = $request->file('image')->getClientOriginalName();
            $this->display_name = $image_name;
            $this->nice_name = strtolower(str_replace(' ', '-', $image_name));
            $this->url = strtolower(str_replace(' ', '-', $image_name));
            //Name Image
            $this->path = '/' . $destination_path . '/' . $image_name;
        }
        $this->desc = NULL;
        if(isset($request->album_cover) && $request->album_cover == 'Y'){
            $this->album_cover = 'Y';
        } else {
            $this->album_cover = 'N';
        }
        $this->gallery_album_id = $album->id;
        try {
            $this->save();
        } catch (ModelNotFoundException $ex) {
            // Error handling code
            return FALSE;
        }
        //Upload Image
        if($request->file('image') !== NULL){
            if (!file_exists($destination_path)) {
                mkdir($destination_path, 0777, true);
            }
            $request->file('image')->move($destination_path, $image_name);
        }
        return TRUE;
    }
    public function upload($file)
    {
        $destination_path = 'public/gallery/' . $this->album->nice_name;
        $image_name = $file->getClientOriginalName();
        $this->display_name = $image_name;
        $this->nice_name = strtolower(str_replace(' ', '-', $image_name));
        $this->url = strtolower(str_replace(' ', '-', $image_name));
        //Name Image
        $this->gallery_album_id = $this->album->id;
        try {
            $this->save();
        } catch (ModelNotFoundException $ex) {
            // Error handling code
            return FALSE;
        }
        if (!file_exists($destination_path)) {
            mkdir($destination_path, 0777, true);
        }
        if(!$file->move($destination_path, $image_name)){
            return FALSE;
        }
        return TRUE;

        $this->path = str_replace(
            'public/', 
            '/storage/', 
            Storage::put('public/images/gallery/' . $this->album->nice_name, 
                $file
            )
        );


    }
}
