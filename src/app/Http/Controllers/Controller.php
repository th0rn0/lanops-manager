<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    function view($view)
    {
        // Set Locale
        if ($locale = \App\Setting::getSiteLocale())
        {
            // $locale_dirs = array_filter(glob('../../../resources/lang/*'), 'is_dir');
            // if(in_array($locale, locale_dirs))
            // {
                App::setLocale($locale);
            // }
        }

        return parent::view($view);
    }
}
