<?php

namespace App\Http\Controllers;

use DB;
use Auth;

use App\NewsArticle;

use App\Http\Requests;
use App\Http\Controllers\Controller;

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
}
