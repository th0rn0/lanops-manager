<?php

namespace App\Http\Controllers\Userapi;


use App\User;
use App\Event;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class MeController extends Controller
{
    /**
     * Show Me
     * @return User
     */
    public function getMe(Request $request)
    {
        $user = auth('sanctum')->user();

        if (!isset($user)) {
            abort(404);
        }


        return [
            'id' => $user->id,
            'firstname' => $user->firstname,
            'surname' => $user->surname,
            'username' => $user->username,
            'username_nice' => $user->username_nice,
            'steamname' => $user->steamname,
            'email' => $user->email,
            'steamid' => $user->steamid,
            'credit_total' => $user->credit_total,
            'admin' => $user->admin,
        ];

    }
}
