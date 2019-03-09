<?php

namespace App\Http\Controllers;

use DB;
use Auth;

use App\NewsArticle;
use App\NewsComment;
use App\NewsTag;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;

class NewsController extends Controller
{
	/**
	 * Show News Index Page
	 * @return View
	 */
	public function index()
	{
		return view('news.index')->withNewsArticles(NewsArticle::all()->reverse());  
	}

	/**
	 * Show News Article Page
	 * @param  NewsArticle $news_article
	 * @return View      
	 */
	public function show(NewsArticle $news_article)
	{
		return view('news.show')->withNewsArticle($news_article);  
	}

	/**
	 * Show News Articles for Given Tag
	 * @param  NewsTag $news_tag
	 * @return View      
	 */
	public function showTag(NewsTag $news_tag)
	{
		foreach (NewsTag::where('tag', $news_tag->tag)->get()->reverse() as $news_tag) {
			$news_articles[] = $news_tag->newsArticle;
		}
		return view('news.tag')->withTag($news_tag->tag)->withNewsArticles($news_articles);  
	}

	/**
	 * Store News Article Comment
	 * @param  NewsArticle $news_article
	 * @param  Request $request
	 * @return View      
	 */
	public function storeComment(NewsArticle $news_article, Request $request)
	{
		if (!Auth::user()) {
			$request->session()->flash('alert-danger', 'Please Login.');
			return Redirect::to('login');
		}
		$rules = [
			'comment'		=> 'required|filled|max:200',
		];
		$messages = [
			'comment.required'		=> 'A Comment is required',
			'comment.filled'		=> 'Comment cannot be empty',
			'comment.max'			=> 'Comment can only be a max of 200 Characters',
		];
		$this->validate($request, $rules, $messages);

		if (!$news_article->storeComment($request->comment, Auth::id())) {
			$request->session()->flash('alert-danger', 'Cannot Post Comment. Please try again.');
			return Redirect::back();
		}
		$request->session()->flash('alert-success', 'Comment Posted and is waiting for Admin Approval!');
		return Redirect::back();
	}

	/**
	 * Report News Article Comment
	 * @param  NewsArticle $news_article
	 * @param  NewsComment $news_comment
	 * @return View      
	 */
	public function reportComment(NewsArticle $news_article, NewsComment $news_comment, Request $request)
	{
		if (!Auth::user()) {
			$request->session()->flash('alert-danger', 'Please Login.');
			return Redirect::to('login');
		}
		if (!$news_comment->report()) {
			$request->session()->flash('alert-danger', 'Cannot Report Comment. Please try again.');
			return Redirect::back();
		}
		$request->session()->flash('alert-success', 'Comment Reported and is waiting Admin Review!');
		return Redirect::back();
	}

	/**
	 * Report News Article Comment
	 * @param  NewsArticle $news_article
	 * @param  NewsComment $news_comment
	 * @return View      
	 */
	public function destroyComment(NewsArticle $news_article, NewsComment $news_comment, Request $request)
	{
		if (!Auth::user()) {
			$request->session()->flash('alert-danger', 'Please Login.');
			return Redirect::to('login');
		}
		if (Auth::id() != $news_comment->user_id) {
			$request->session()->flash('alert-danger', 'This is not your comment to delete!');
			return Redirect::back();
		}
		if (!$news_comment->delete()) {
			$request->session()->flash('alert-danger', 'Cannot Delete Comment. Please try again.');
			return Redirect::back();
		}
		$request->session()->flash('alert-success', 'Comment Deleted!');
		return Redirect::back();
	}
}
