<?php

if (env('ENABLE_HTTPS') || env('FORCE_APP_HTTPS'))
{
    $usehttps = true;
}
else
{
    $usehttps = false;
}

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

    'https' => $usehttps

];
