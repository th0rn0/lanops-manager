<?php

namespace App\Http\Controllers\Admin;

use DB;
use Auth;
use Session;
use Storage;
use Image;
use File;

use App\Game;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class GameTemplatesController extends Controller
{
    /**
     * Show Game Templates Index Page
     * @return Redirect
     */
    public function index()
    {
        return view('admin.games.gametemplates.index')
            ->withGames(Game::paginate(20));
    }

    /**
     * Show Game Page
     * @return Redirect
     */
    public function show(Game $game)
    {
        return view('admin.games.gametemplates.show')
            ->withGame($game);
    }

    /**
     * Delete Game from Database
     * @param  Game  $game
     * @return Redirect
     */
    public function createFromTemplate(Game $game)
    {
        Session::flash('alert-success', 'Successfully deleted Game!');
        return Redirect::to('admin/games/gametemplates');
    }
}
