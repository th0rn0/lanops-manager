<?php

namespace App\Http\Controllers\Admin;

use DB;
use Auth;
use Session;
use Form;
use Input;
use Redirect;
use Validator;

use App\News;
use App\User;
use App\Event;
use App\EventParticipant;

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
		$user = Auth::user();
		$news_items = News::all();
		$news_items->load('user');
		return view('admin.news.main')->withUser($user)->withNews($news_items);  
	}

	/**
	 * Add News to DB
	 * @param  Request $request
	 * @return Redirect
	 */
	public function store(Request $request)
	{
		$user = Auth::user();

		// Define rules
		$rules = array(
			'title'		=> array( 'required', 'unique:news_feed' ),
			'article'	=> array( 'required' ),
		);

		// Pass input to validator
		$validator = Validator::make(Input::all(), $rules);

		// Test if input fails
		if ($validator->fails()) {
			$request->session()->flash('alert-danger', 'Please ensure news Title is unique.');
			return Redirect::to('admin/news');
		}
		// Retrieve input data
		$title = Input::get('title');
		$article = Input::get('article');

		// Create new instance of News() class
		$news_article = new News();
		$news_article->title = $title;
		$news_article->article = $article;
		$news_article->user_id = $user->id;

		// Save to database
		if($news_article->save()){
			$request->session()->flash('alert-success', 'News item saved.');
			return Redirect::to('admin/news');
		} else {
			$request->session()->flash('alert-danger', 'Something went wrong. Please try again later.');
			return Redirect::to('admin/news');
		}
	}
}
