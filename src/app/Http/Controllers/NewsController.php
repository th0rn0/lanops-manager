<?php

namespace App\Http\Controllers;

use DB;
use Auth;

use App\NewsArticle;

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

	public function postComment(NewsArticle $news_article, Request $request)
	{
		if (!Auth::user()) {
			$request->session()->flash('alert-danger', 'Please Login.');
			return Redirect::to('login');
		}
		$rules = [
			'comment'		=> 'required|filled',
		];
		$messages = [
			'comment.required'		=> 'A Comment is required',
			'comment.filled'		=> 'Comment cannot be empty',
		];
		$this->validate($request, $rules, $messages);

		if (!$news_article->postComment($request->comment, Auth::id())) {
			$request->session()->flash('alert-danger', 'Cannot post comment. Please try again.');
			return Redirect::back();
		}
		$request->session()->flash('alert-success', 'Comment Posted!');
		return Redirect::back();
	}
}
