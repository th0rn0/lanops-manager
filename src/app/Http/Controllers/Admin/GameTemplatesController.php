<?php

namespace App\Http\Controllers\Admin;

use DB;
use Auth;
use Illuminate\Database\Seeder;
use Session;
use Storage;
use Image;
use File;
use Helpers;

use App\Game;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

use HaydenPierce\ClassFinder\ClassFinder;

class GameTemplatesController extends Controller
{
    /**
     * Show Game Templates Index Page
     * @return Redirect
     */
    public function index()
    {
        $classenames = ClassFinder::getClassesInNamespace('Database\Seeders\GameTemplates');
        $gameTemplates = collect();

        foreach ($classenames as $classname)
        {
            $gameTemplates->push(new $classname);
        }

        return view('admin.games.gametemplates.index')
            ->withGameTemplates($gameTemplates);
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
     * @return Redirect
     */
    public function deploy(Request $request)
    {
        dd($request->gameTemplateClass);

        //


        Session::flash('alert-success', 'Successfully deleted Game!');
        return Redirect::to('admin/games/gametemplates');
    }
}
