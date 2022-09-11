<?php

namespace App\Http\Middleware;

use App;
use Closure;
use Auth;

class Gameserver
{

    public function handle($request, Closure $next)
    {
        if (auth('sanctum')->user() != NULL && get_class(auth('sanctum')->user()) == "App\GameServer") {
            return $next($request);
        }

        return redirect('/');
    }
}
