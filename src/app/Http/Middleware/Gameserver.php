<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class Gameserver
{

    public function handle($request, Closure $next)
    {
        if ((auth('sanctum')->user() != NULL && auth('sanctum')->user())) {
            return $next($request);
        }

        return redirect('/');
    }
}
