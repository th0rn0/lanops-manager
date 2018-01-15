<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\News;
use Form;
use Input;
use Redirect;
use Validator;

use App\Http\Requests;

class NewsController extends Controller
{

	/**
	 * Show News
	 * @return News $news
	 */
	public function index()
	{
		$news = News::all();
		return $news;
	}

	/**
	* Show the form for creating a new resource.
	* @return \Illuminate\Http\Response
	*/
	public function create()
	{
		return view('admin.news.create');  
	}

	/**
	* Store a newly created resource in storage.
	* @param  \Illuminate\Http\Request  $request
	* @return \Illuminate\Http\Response
	*/
  	public function store(Request $request)
  	{
		// Define rules
		$rules = array(
			'title' => array( 'required', 'unique:news_feed' ),
			'article' => array( 'required' ),
		);

		// Pass input to validator
		$validator = Validator::make(Input::all(), $rules);

		// Test if input fails
		if ( $validator->fails() ) {
			return Redirect::route('admin.news.create');
		}


		// Retrieve input data
		$title = Input::get('title');
		$article = Input::get('article');

		// Create new instance of News() class
		$news_article = new News();
		$news_article->title = $title;
		$news_article->article = $article;

		// Save to database
		$news_article->save();

		return Redirect::route('admin.news.index');
  	}

	/**
	* Display the specified resource.
	* @param  News  $news
	* @return \Illuminate\Http\Response
	*/
	public function show(News $news)
	{
		return $news;
	}

	/**
	* Show the form for editing the specified resource.
	* @param  int  $id
	* @return \Illuminate\Http\Response
	*/
	public function edit($id)
	{
		$news = News::find($id);
		return view('admin.news.edit')->withNews($news);
	}

	/**
	* Update the specified resource in storage.
	* @param  \Illuminate\Http\Request  $request
	* @param  int  $id
	* @return \Illuminate\Http\Response
	*/
	public function update(Request $request, $id)
	{
	  
	}

	/**
	* Remove the specified resource from storage.
	* @param  int  $id
	* @return \Illuminate\Http\Response
	*/
	public function destroy($id)
	{
	  
	}
}