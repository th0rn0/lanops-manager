<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\GalleryAlbum;

use App\Http\Controllers\Controller;

class GalleryController extends Controller
{
    /**
     * Show Gallery Index Page
     * @return View
     */
    public function index()
    {
        $event = Event::where('start', '>=', date("Y-m-d 00:00:00"))->first();
        $albums = GalleryAlbum::all();
        return view('gallery.index')
            ->withAlbums($albums)
            ->withEvent($event);
    }

    /**
     * Show Gallery Page
     * @param  GalleryAlbum $album
     * @return View
     */
    public function show(GalleryAlbum $album)
    {
        return view('gallery.show')
            ->withAlbum($album);
    }
}
