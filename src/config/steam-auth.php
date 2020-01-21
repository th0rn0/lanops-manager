<?php

return [

    /*
     * Redirect url after login
     */
    'redirect_url' => '/login/steam',
    /*
     *  Api Key (http://steamcommunity.com/dev/apikey)
     */
    'api_key' => '',
    /*
     * Is using https?
     */
    'https' => env('ENABLE_HTTPS')

];
