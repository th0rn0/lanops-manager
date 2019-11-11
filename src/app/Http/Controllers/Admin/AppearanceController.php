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
use App\Appearance;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class AppearanceController extends Controller
{
    /**
     * Show Appearance Index Page
     * @return Redirect
     */
    public function index()
    {
        $cssVariables = Appearance::getCssVariables();
        $sortedCssVariables['primary'] = $cssVariables->filter(function ($item) {
            return false !== stristr($item->key, 'color_primary');
        });
        $sortedCssVariables['secondary'] = $cssVariables->filter(function ($item) {
            return false !== stristr($item->key, 'color_secondary');
        });
        $sortedCssVariables['body'] = $cssVariables->filter(function ($item) {
            return false !== stristr($item->key, 'color_body');
        });
        $sortedCssVariables['header'] = $cssVariables->filter(function ($item) {
            return false !== stristr($item->key, 'color_header');
        });
        return view('admin.settings.appearance')
            ->withUserOverrideCss(Appearance::getCssOverride())
            ->withCssVariables($sortedCssVariables);
        ;
    }
    
    /**
     * Recompile CSS from SCSS
     * @return Redirect
     */
    public function cssRecompile()
    {
        if (!Appearance::cssRecompile()) {
            Session::flash('alert-danger', 'Could recompile CSS. Please try again.');
            return Redirect::back();
        }
        Session::flash('alert-success', 'Successfully recompiled the CSS!');
        return Redirect::back();
    }

    /**
     * Add Additional CSS Override
     * @param Request $request
     * @return Redirect
     */
    public function cssOverride(Request $request)
    {
        $rules = [
            'css'   => 'required',
        ];
        $messages = [
            'css.required'      => 'CSS is required.',
        ];
        $this->validate($request, $rules, $messages);
        if (!Appearance::saveCssOverride($request->css)) {
            Session::flash('alert-danger', 'Could not save CSS. Please try again.');
            return Redirect::back();
        }
        if (!Appearance::cssRecompile()) {
            Session::flash('alert-danger', 'Could recompile CSS. Please try again.');
            return Redirect::back();
        }
        Session::flash('alert-success', 'Successfully recompiled the CSS!');
        return Redirect::back();
    }

    /**
     * Update CSS Variables
     * @param Request $request
     * @return Redirect
     */
    public function cssVariables(Request $request)
    {
        if (!Appearance::saveCssVariables($request->css_variables)) {
            Session::flash('alert-danger', 'Could not save CSS Variables. Please try again.');
            return Redirect::back();
        }
        if (!Appearance::cssRecompile()) {
            Session::flash('alert-danger', 'Could recompile CSS. Please try again.');
            return Redirect::back();
        }
        Session::flash('alert-success', 'Successfully saved CSS Variables!');
        return Redirect::back();
    }
}
