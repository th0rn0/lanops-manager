<?php

return [

    /*
     * Redirect url after login
     */
    'redirect_url' => '/login/steam',
    /*
     *  Api Key (http://steamcommunity.com/dev/apikey)
     */
    'api_key' => env('STEAM_API_KEY'),
    /*
     * Is using https?
     */
    'https' => env('STEAM_ENABLE_HTTPS', true)

];
