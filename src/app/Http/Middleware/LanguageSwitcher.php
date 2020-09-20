<?php

namespace App\Http\Middleware;

use Closure;
use Session;
use App;
use Config;
use App\Setting;

class LanguageSwitcher
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // if (!Session::has('locale'))
        //  {
        //    Session::put('locale',Config::get('app.locale'));
        // }
        // App::setLocale(session('locale'));
       
        if ($locale = Setting::getSiteLocale())
        {
            // $locale_dirs = array_filter(glob('../../../resources/lang/*'), 'is_dir');
            // if(in_array($locale, locale_dirs))
            // {
            App::setLocale($locale);
            // }
        }
       

        return $next($request);
    }
}

  // function view($view)
    // {
    //     // Set Locale
    //     if ($locale = \App\Setting::getSiteLocale())
    //     {
    //         // $locale_dirs = array_filter(glob('../../../resources/lang/*'), 'is_dir');
    //         // if(in_array($locale, locale_dirs))
    //         // {
    //             App::setLocale($locale);
    //         // }
    //     }

    //     return parent::view($view)->withLocale($locale);
    // }