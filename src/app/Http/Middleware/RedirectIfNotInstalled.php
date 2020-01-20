<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Settings;

class RedirectIfNotInstalled
{

    public function handle($request, Closure $next)
    {

        if (!Settings::isInstalled()) {
        	return redirect('/install');
        }

        return $next($request);
    }
}
