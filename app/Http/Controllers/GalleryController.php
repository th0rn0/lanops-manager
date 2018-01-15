<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use Auth;
use App\Event;
use App\GalleryAlbum;
use App\GalleryAlbumImage;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Cviebrock\EloquentSluggable\Sluggable;

class GalleryController extends Controller
{
	use Sluggable;
	
	/**
	 * Return the sluggable configuration array for this model.
	 * @return array
	 */
	public function sluggable()
	{
		return [
			'slug' => [
				'source' => 'nice_name'
			]
		];
	}
	
	/**
	 * Show Gallery Index Page
	 * @return View
	 */
	public function index()
	{
		$event = Event::where('start', '>=', date("Y-m-d 00:00:00"))->first();
		$albums = GalleryAlbum::all();
		return view('gallery.index')->withAlbums($albums)->withEvent($event);  
	}

	/**
	 * Show Gallery Page
	 * @param  $slug
	 * @return View      
	 */
	public function show($slug)
	{
		if(!is_numeric($slug)){
			$album = GalleryAlbum::where('slug', $slug)->first();
		} else {
			$album = GalleryAlbum::where('id', $slug)->first();
		}
		return view('gallery.show')->withAlbum($album);  
	}
}
