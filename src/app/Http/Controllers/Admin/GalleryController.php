<?php

namespace App\Http\Controllers\Admin;

use DB;
use Auth;
use Storage;
use Input;
use Validator;
use Session;

use App\User;
use App\Event;
use App\GalleryAlbum;
use App\GalleryAlbumImage;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    /**
     * Show Gallery Index Page
     * @return view
     */
    public function index()
    {
        return view('admin.gallery.index')
            ->withAlbums(GalleryAlbum::paginate(20))
        ;
    }
    
    /**
     * Show Gallery Page
     * @return view
     */
    public function show(GalleryAlbum $album)
    {
        return view('admin.gallery.show')
            ->withAlbum($album)
            ->withImages($album->images()->paginate(10))
        ;
    }
    
    /**
     * Store Gallery to DB
     * @param  Request $request
     * @return Redirect
     */
    public function store(Request $request)
    {
        $rules = [
            'name'          => 'required',
            'description'   => 'required'
        ];
        $messages = [
            'name.required'         => 'Name is required',
            'description.required'  => 'Description is required'
        ];
        $this->validate($request, $rules, $messages);

        $album              = new GalleryAlbum();
        $album->name        = $request->name;
        $album->description = $request->description;

        if (!$album->save()) {
            Session::flash('alert-danger', 'Cannot save Gallery!');
            return Redirect::to('admin/gallery');
        }

        Session::flash('alert-success', 'Successfully saved Gallery!');
        return Redirect::to('admin/gallery/' . $album->slug);
    }
    
    /**
     * Update Gallery
     * @param  GalleryAlbum           $album
     * @param  GalleryAlbumImage|null $image
     * @param  Request                $request
     * @return Redirect
     */
    public function update(GalleryAlbum $album, GalleryAlbumImage $image = null, Request $request)
    {
        $rules = [
            'name'          => 'filled',
            'description'   => 'filled',
            'status'        => 'in:draft,published',
        ];
        $messages = [
            'name.filled'           => 'Name cannot be empty',
            'description.filled'    => 'Description cannot be empty',
            'status.in'             => 'Status must be draft or published',
        ];
        $this->validate($request, $rules, $messages);

        if (isset($request->name)) {
            $album->name        = $request->name;
        }

        if (isset($request->description)) {
            $album->description = $request->description;
        }

        if (isset($request->status)) {
            $album->status      = $request->status;
        }

        if (isset($request->event_id)) {
            $album->event_id    = $request->event_id;
        }

        if (!$album->save()) {
            Session::flash('alert-danger', 'Cannot update Gallery!');
            return Redirect::back();
        }

        Session::flash('alert-success', 'Successfully updated Gallery!');
        return Redirect::back();
    }

    /**
     * Delete Gallery
     * @param  GalleryAlbum $album
     * @return Redirect
     */
    public function destroy(GalleryAlbum $album)
    {
        if (!$album->delete()) {
            Session::flash('alert-danger', 'Cannot delete Gallery!');
            return Redirect::back();
        }

        Session::flash('alert-success', 'Successfully deleted Gallery!');
        return Redirect::back();
    }

    /**
     * Upload Image to Gallery
     * @param  GalleryAlbum $album
     * @param  Request      $request
     * @return Redirect
     */
    public function uploadImage(GalleryAlbum $album, Request $request)
    {
        $rules = [
            'image.*'   => 'image',
        ];
        $messages = [
            'image.*.image' => 'Venue Image must be of Image type',
        ];
        $this->validate($request, $rules, $messages);

        $files = Input::file('images');
        //Keep a count of uploaded files
        $file_count = count($files);
        //Counter for uploaded files
        $uploadcount = 0;
        foreach ($files as $file) {
            $imageName  = $file->getClientOriginalName();
            $destinationPath = 'public/images/gallery/' . $album->name;

            $image                          = new GalleryAlbumImage();
            $image->display_name            = $imageName;
            $image->nice_name               = $image->url = strtolower(str_replace(' ', '-', $imageName));
            $image->gallery_album_id        = $album->id;
            $image->path = str_replace(
                'public/',
                '/storage/',
                Storage::put(
                    $destinationPath,
                    $file
                )
            );
            $image->save();
            
            $uploadcount++;
        }
        if ($uploadcount != $file_count) {
            Session::flash('alert-danger', 'Upload unsuccessful!');
            return Redirect::to('admin/gallery/' . $album->slug);
        }

        Session::flash('alert-success', 'Upload successful!');
        return Redirect::to('admin/gallery/' . $album->slug);
    }

    /**
     * Delete Image from Gallery
     * @param  GalleryAlbum      $album
     * @param  GalleryAlbumImage $image
     * @return Redirect
     */
    public function destroyImage(GalleryAlbum $album, GalleryAlbumImage $image)
    {
        if (!$image->delete()) {
            Session::flash('alert-danger', 'Cannot delete Image!');
            return Redirect::back();
        }

        Session::flash('alert-success', 'Successfully deleted Image!');
        return Redirect::back();
    }

    /**
     * Update Image from Gallery
     * @param  GalleryAlbum      $album
     * @param  GalleryAlbumImage $image
     * @param  Request           $request
     * @return Redirect
     */
    public function updateImage(GalleryAlbum $album, GalleryAlbumImage $image, Request $request)
    {
        //DEBUG - Refactor - replace iamge name as well!
        $image->display_name  = $request->name;
        $image->nice_name     = strtolower(str_replace(' ', '-', $request->name));
        $image->desc          = $request->desc;

        if (isset($request->album_cover) && $request->album_cover) {
            $album->setAlbumCover($image->id);
        }

        if (!$image->save()) {
            Session::flash('alert-danger', 'Could not update!');
            return Redirect::back();
        }

        Session::flash('alert-success', 'Successfully updated!');
        return Redirect::back();
    }
}
