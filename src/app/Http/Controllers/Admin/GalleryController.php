<?php

namespace App\Http\Controllers\Admin;

use Input;
use Image;
use Session;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

use App\Models\GalleryAlbum;
use App\Models\GalleryAlbumImage;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;

use Spatie\Image\Manipulations;

use App\Jobs\ProcessUploadedImages;

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
            ->withImages($album->getMedia('images'))
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
     * @param  Request                $request
     * @return Redirect
     */
    public function update(GalleryAlbum $album, Request $request)
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
        return Redirect::to('admin/gallery');
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

        $fileAdders = $album->addMultipleMediaFromRequest(['images'])
            ->each(function ($fileAdder) {
                $fileAdder->toMediaCollection('images');
            });

        Session::flash('alert-success', 'Upload successful! Processing images please wait!');
        return Redirect::to('admin/gallery/' . $album->slug);
    }

    /**
     * Delete Image from Gallery
     * @param  GalleryAlbum     $album
     * @param  Media            $image
     * @return Redirect
     */
    public function destroyImage(GalleryAlbum $album, Media $image)
    {
        $image->clearMediaCollection();
        if (!$image->delete()) {
            Session::flash('alert-danger', 'Cannot delete Image!');
            return Redirect::back();
        }

        Session::flash('alert-success', 'Successfully deleted Image!');
        return Redirect::back();
    }

    /**
     * Ingest images from upload dir
     * @param  GalleryAlbum      $album
     * @return Redirect
     */
    public function ingestImages(GalleryAlbum $album) {
        ProcessUploadedImages::dispatch($album);
        Session::flash('alert-info', 'Upload Started! Please check back in a while...');
        return Redirect::to('admin/gallery/' . $album->slug);
    }
}
