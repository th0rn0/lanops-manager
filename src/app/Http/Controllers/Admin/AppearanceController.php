<?php

namespace App\Http\Controllers\Admin;

use DB;
use Auth;
use Session;
use Redirect;
use Settings;
use Input;

use App\User;
use App\Setting;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Leafo\ScssPhp\Compiler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AppearanceController extends Controller
{
	/**
	 * Show Appearance Index Page
	 * @return Redirect
	 */
	public function index()
	{
		return view('admin.appearance.index');
	}
	
	/**
	 * Recompile CSS from SCSS
	 * @return Redirect
	 */
	// TODO
	// Move to Settings/layout model or settings helper
	// Permissions from NPM are wrong
	// Run me on boot plz
	public function recompileCSS(Compiler $scss)
	{
		$scss->setImportPaths('/web/html/resources/assets/sass/');
		$scss->setSourceMap(Compiler::SOURCE_MAP_FILE);
		$css_templates = ['app', 'admin'];
		foreach ($css_templates as $css_template) {
		    $scss->setSourceMapOptions(array(
		        'sourceMapWriteTo'  => config('filesystems.disks.compiled-css.root') . '/'. str_replace("/", "_", $css_template) . ".css.map",
		        'sourceMapFilename' => $css_template . '.css',
		        'sourceMapBasepath' => config('filesystems.disks.compiled-css.root'),
		        'sourceRoot'        => '/',
		    ));
			Storage::disk('compiled-css')->delete($css_template . '.css');
			Storage::disk('compiled-css')->delete($css_template . '.css.map');
			Storage::disk('compiled-css')->put($css_template . '.css', $scss->compile('@import "' . $css_template . '.scss";'));
		}
		Session::flash('alert-success', 'Successfully recompiled the CSS!');
		return Redirect::back(); 
	}
}
