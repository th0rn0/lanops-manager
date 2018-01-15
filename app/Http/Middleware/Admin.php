<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class Admin {

    public function handle($request, Closure $next)
    {

        if (Auth::check() && Auth::user()->getAdmin()) {
            return $next($request);
        }

        return redirect('/');

    }

}