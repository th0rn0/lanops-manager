<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Settings;
use Colors;

class NoPhoneNumber
{

    public function handle($request, Closure $next)
    {
        if (Settings::isAuthRequirePhonenumberEnabled() && Auth::user()->phonenumber == NULL) {
            $request->session()->put('eventula_req_url', $request->getRequestUri());
			return redirect('/account/email');
        }
		return $next($request);
    }
}
