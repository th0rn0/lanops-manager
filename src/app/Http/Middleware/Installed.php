<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Settings;

class Installed
{

    public function handle($request, Closure $next)
    {

        if (!Settings::isInstalled()) {
        	return redirect('/install');
        }
        return $next($request);
    }
}
