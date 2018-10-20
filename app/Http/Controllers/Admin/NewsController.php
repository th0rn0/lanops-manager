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
		return view('admin.news.index')->withNewsArticles(NewsArticle::all());
	}

	/**
	 * Show News Article Page
	 * @return View
	 */
	public function show(NewsArticle $news_article)
	{
		return view('admin.news.show')->withNewsArticle($news_article);
	}

	/**
	 * Add News Articleto Database
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

		$news_article = new NewsArticle;
		$news_article->title = $request->title;
		$news_article->article = $request->article;
		$news_article->user_id = Auth::id();
		if (!$news_article->save()) {
			Session::flash('alert-danger', 'Cannot Save News Article!');
			return Redirect::to('/admin/events/');
		}
		Session::flash('alert-success', 'Successfully saved News Article!');
		return Redirect::to('/admin/news/');
	}

	/**
	 * Update News Article
	 * @param  Event   $event
	 * @param  Request $request
	 * @return Redirect
	 */
	public function update(NewsArticle $news_article, Request $request)
	{
		$rules = [
			'title'		=> 'filled|unique:news_feed,title',
			'article'	=> 'filled',
		];
		$messages = [
			'title.filled'			=> 'Title cannot be empty',
			'article.filled'		=> 'Article cannot be empty',
		];
		$this->validate($request, $rules, $messages);

		$news_article->title 	= $request->title;
		$news_article->article 	= $request->article;

		if (!$news_article->save()) {
			Session::flash('alert-danger', 'Cannot update News Article!');
			return Redirect::to('admin/news/' . $news_article->slug);
		}

		Session::flash('alert-success', 'Successfully updated News Article!');
		return Redirect::to('admin/news/' . $news_article->slug);
	}

	/**
	 * Delete News Article from Database
	 * @param  Event  $news_article
	 * @return Redirect
	 */
	public function destroy(NewsArticle $news_article)
	{
		if (!$news_article->delete()) {
			Session::flash('alert-danger', 'Cannot delete News Article!');
			return Redirect::back();
		}

		Session::flash('alert-success', 'Successfully deleted News Article!');
		return Redirect::to('admin/news/');
	}
}
