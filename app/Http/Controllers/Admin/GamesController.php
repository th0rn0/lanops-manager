<?php

namespace App\Http\Controllers\Admin;

use DB;
use Auth;
use Session;
use Storage;
use Input;

use App\Game;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class GamesController extends Controller
{
	/**
	 * Show Games Index Page
	 * @return Redirect
	 */
	public function index()
	{
		return view('admin.games.index')->withGames(Game::all());
	}

	/**
   	 * Store Game to Database
   	 * @param  Event   $event
   	 * @param  Request $request
   	 * @return Redirect
   	 */
	public function store(Request $request)
	{
		$rules = [
			'name'				=> 'required',
			'image_header'		=> 'image',
			'image_thumbnail'	=> 'image',
		];
		$messages = [
			'name.required'			=> 'Game name is required',
			'image_header.image'	=> 'Header image must be a Image',
			'image_thumbnail.image'	=> 'Thumbnail image must be a Image'
		];
		$this->validate($request, $rules, $messages);

		$game = new Game();

		$game->name = $request->name;
		$game->description = @(trim($request->description) == '' ? null : $request->description);
		$game->version = @(trim($request->version) == '' ? null : $request->version);
		$game->active = true;

		if (!$game->save()) {
			Session::flash('alert-danger', 'Could not save Game!'); 
			return Redirect::back();
		}

		if (Input::file('image_thumbnail')) {
			$image_name	= $game->slug . '_thumbnail' . Input::file('image_thumbnail')->getClientOriginalExtension();
			$destination_path = 'public/images/games/' . $game->slug;
			$game->image_thumbnail_path = str_replace(
				'public/', 
				'/storage/', 
				Storage::put($destination_path, 
						Input::file('image_thumbnail')
				)
			);
			if (!$game->save()) {
				Session::flash('alert-danger', 'Could not save Game thumbnail!'); 
				return Redirect::back();
			}
		}

		if (Input::file('image_header')) {
			$image_name	= $game->slug . '_header' . Input::file('image_header')->getClientOriginalExtension();
			$destination_path = 'public/images/games/' . $game->slug;
			$game->image_header_path = str_replace(
				'public/', 
				'/storage/', 
				Storage::put($destination_path, 
						Input::file('image_header')
				)
			);
			if (!$game->save()) {
				Session::flash('alert-danger', 'Could not save Game Header!'); 
				return Redirect::back();
			}
		}
		Session::flash('alert-success', 'Successfully saved Game!');
		return Redirect::to('admin/games/' . $game->slug);
	}
}