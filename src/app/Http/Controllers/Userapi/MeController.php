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
            'name' => $user->username,
            'admin' => $user->admin,
        ];

    }
}
