<?php

namespace App\Http\Controllers\Admin;

use DB;
use Auth;
use Session;
use Settings;

use App\User;
use App\Event;
use App\NewsArticle;
use App\NewsComment;
use App\GalleryAlbum;
use App\GalleryAlbumImage;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;

use FacebookPageWrapper as Facebook;

class newsController extends Controller
{
	/**
	 * Show News Index Page
	 * @return View
	 */
	public function index()
	{
		return view('admin.news.index')
			->withNewsArticles(NewsArticle::all())
			->withFacebookLinked(Facebook::isLinked())
		;
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
			'tags'		=> 'filled',
		];
		$messages = [
			'title.required'	=> 'Title is required,',
			'article.required'	=> 'Article is required,',
			'tags.filled'		=> 'You must add Tags.',
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
		if (!$news_article->storeTags(explode(',', $request->tags))) {
			$news_article->delete();
			Session::flash('alert-danger', 'Cannot Save News Article!');
			return Redirect::to('/admin/events/');
		}

		if (
			(
				isset($request->post_to_facebook) && 
				$request->post_to_facebook 
			) && 
			(
				Facebook::isEnabled() && 
				Facebook::isLinked()
			)
		) {
			if (!Facebook::postNewsArticleToPage($news_article->title, $news_article->article, $news_article->slug)) {
				Session::flash('alert-danger', 'Facebook SDK returned an error');
		 		return Redirect::back();
			}
		}

		Session::flash('alert-success', 'Successfully Saved News Article!');
		return Redirect::to('/admin/news/');
	}

	/**
	 * Update News Article
	 * @param  NewsArticle $news_article
	 * @param  Request $request
	 * @return Redirect
	 */
	public function update(NewsArticle $news_article, Request $request)
	{
		$rules = [
			'title'		=> 'filled',
			'article'	=> 'filled',
			'tags'		=> 'filled',
		];
		$messages = [
			'title.filled'			=> 'Title cannot be empty.',
			'article.filled'		=> 'Article cannot be empty.',
			'tags.filled'			=> 'You must add Tags.',
		];
		$this->validate($request, $rules, $messages);

		$news_article->title 	= $request->title;
		$news_article->article 	= $request->article;

		if (!$news_article->storeTags(explode(',', $request->tags)) && !$news_article->save()) {
			Session::flash('alert-danger', 'Cannot Update News Article!');
			return Redirect::to('admin/news/' . $news_article->slug);
		}

		Session::flash('alert-success', 'Successfully Updated News Article!');
		return Redirect::to('admin/news/' . $news_article->slug);
	}

	/**
	 * Delete News Article from Database
	 * @param  NewsArticle $news_article
	 * @return Redirect
	 */
	public function destroy(NewsArticle $news_article)
	{
		if (!$news_article->delete()) {
			Session::flash('alert-danger', 'Cannot Delete News Article!');
			return Redirect::back();
		}

		Session::flash('alert-success', 'Successfully Deleted News Article!');
		return Redirect::to('admin/news/');
	}


	/**
	 * Delete News Article Comment from Database
	 * @param  NewsArticle  $news_article
	 * @param  NewsComment  $news_comment
	 * @return Redirect
	 */
	public function destroyComment(NewsArticle $news_article, NewsComment $news_comment)
	{
		if (!$news_comment->delete()) {
			Session::flash('alert-danger', 'Cannot Delete News Article Comment!');
			return Redirect::back();
		}

		Session::flash('alert-success', 'Successfully Deleted News Article Comment!');
		return Redirect::back();
	}
}
