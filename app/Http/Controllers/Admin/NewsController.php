<?php

namespace App\Http\Controllers\Admin;

use DB;
use Auth;
use Session;

use App\User;
use App\Event;
use App\NewsArticle;
use App\GalleryAlbum;
use App\GalleryAlbumImage;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;

class newsController extends Controller
{
	/**
	 * Show News Index Page
	 * @return View
	 */
	public function index()
	{
		return view('admin.news.index')->withNews(NewsArticle::all());
	}

	/**
	 * Show News Item Page
	 * @return View
	 */
	public function show(NewsArticle $news_item)
	{
		return view('admin.news.show')->withNewsItem($news_item);
	}

	/**
	 * Add News Item to Database
	 * @param  Request $request
	 * @return Redirect
	 */
	public function store(Request $request)
	{
		$rules = [
			'title' 	=> 'required|unique:news_feed,title',
			'article'	=> 'required',
		];
		$messages = [
			'title.required'	=> 'Title is required,',
			'article.required'	=> 'Article is required,',
		];
		$this->validate($request, $rules, $messages);

		$news_item = new NewsArticle;
		$news_item->title = $request->title;
		$news_item->article = $request->article;
		$news_item->user_id = Auth::id();
		if (!$news_item->save()) {
			Session::flash('alert-danger', 'Cannot Save News Article!');
			return Redirect::to('/admin/events/');
		}
		Session::flash('alert-success', 'Successfully saved News Article!');
		return Redirect::to('/admin/news/');
	}
}
