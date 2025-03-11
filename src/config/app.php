<?php
return [

    'name'              => env('APP_NAME', 'LanOps'),
    'tagline'           => env('APP_TAGLINE','Lans in South Yorkshire!'),
    'env'               => env('APP_ENV', 'production'),
    'debug'             => env('APP_DEBUG', false),
    'url'               => env('APP_URL', 'localhost'),
    'timezone'          => 'UTC',
    'locale'            => 'en',
    'fallback_locale'   => 'en',
    'key'               => env('APP_KEY'),
    'cipher'            => 'AES-256-CBC',
    'log'               => env('APP_LOG', 'errorlog'),

    'logo'              => '/images/logo_main.png',
    'favicon'           => '/images/favicon.ico',
    'basket_name'       => str_replace(" ", "_", strtolower(env('APP_NAME', 'LanOps'))) . '-basket',

    'discord_link'      => env('DISCORD_LINK', ""),
    'discord_id'        => env('DISCORD_ID', ""),

    'facebook_link'     => env('FACEBOOK_LINK',''),

    'steam_link'        => env('STEAM_LINK',''),

    'youtube_link'       => env('YOUTUBE_LINK',''),

    'bsky_link'         => env('BSKY_LINK'),

    'currency'          => env('APP_CURRENCY','GBP'),
    'currency_symbol'   => env('APP_CURRENCY','£'),

    'discord_bot_url'   => env('DISCORD_BOT_URL', ''),
    'discord_bot_user' => env('DISCORD_BOT_USER', ''),
    'discord_bot_pass' => env('DISCORD_BOT_PASS', ''),
    'discord_bot_secret' => env('DISCORD_BOT_SECRET'),
    'discord_auth_url' => env('DISCORD_AUTH_URL', ''),
    'discord_reditect_url' => env('DISCORD_REDIRECT_URL', ''),
    'discord_client_id' => env('DISCORD_CLIENT_ID', ''),
    'discord_client_secret' => env('DISCORD_CLIENT_SECRET', ''),
    'discord_scope' => env('DISCORD_SCOPE', 'identify'),

    'refer_a_friend_discount' => env('REFER_A_FRIEND_DISCOUNT', 10),

    'seo_description' => env('SEO_DESCRIPTION', ''),
    'seo_keywords' => env('SEO_KEYWORDS', 'UK Lan, LAN, LAN Party, South Yorkshire, Gaming, lanops, UK, community'),
    
    'google_tag_id' => env('GOOGLE_TAG_ID', null),

    /*
    |--------------------------------------------------------------------------
    | Autoloaded Service Providers
    |--------------------------------------------------------------------------
    */

    'providers' => [

        /*
         * Laravel Framework Service Providers...
         */
        Illuminate\Auth\AuthServiceProvider::class,
        Illuminate\Broadcasting\BroadcastServiceProvider::class,
        Illuminate\Bus\BusServiceProvider::class,
        Illuminate\Cache\CacheServiceProvider::class,
        Illuminate\Foundation\Providers\ConsoleSupportServiceProvider::class,
        Illuminate\Cookie\CookieServiceProvider::class,
        Illuminate\Database\DatabaseServiceProvider::class,
        Illuminate\Encryption\EncryptionServiceProvider::class,
        Illuminate\Filesystem\FilesystemServiceProvider::class,
        Illuminate\Foundation\Providers\FoundationServiceProvider::class,
        Illuminate\Hashing\HashServiceProvider::class,
        Illuminate\Mail\MailServiceProvider::class,
        Illuminate\Notifications\NotificationServiceProvider::class,
        Illuminate\Pagination\PaginationServiceProvider::class,
        Illuminate\Pipeline\PipelineServiceProvider::class,
        Illuminate\Queue\QueueServiceProvider::class,
        Illuminate\Redis\RedisServiceProvider::class,
        Illuminate\Auth\Passwords\PasswordResetServiceProvider::class,
        Illuminate\Session\SessionServiceProvider::class,
        Illuminate\Translation\TranslationServiceProvider::class,
        Illuminate\Validation\ValidationServiceProvider::class,
        Illuminate\View\ViewServiceProvider::class,
        Collective\Html\HtmlServiceProvider::class,
        Invisnik\LaravelSteamAuth\SteamServiceProvider::class,
        Laravel\Socialite\SocialiteServiceProvider::class,
        SimpleSoftwareIO\QrCode\QrCodeServiceProvider::class,
        Cviebrock\EloquentSluggable\ServiceProvider::class,
        Ignited\LaravelOmnipay\LaravelOmnipayServiceProvider::class,
        Intervention\Image\ImageServiceProvider::class,
        Mews\Captcha\CaptchaServiceProvider::class,
        /*
         * Application Service Providers...
         */
        App\Providers\AppServiceProvider::class,
        App\Providers\AuthServiceProvider::class,
        App\Providers\EventServiceProvider::class,
        App\Providers\RouteServiceProvider::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Class Aliases
    |--------------------------------------------------------------------------
    */

    'aliases' => [

        'App'           => Illuminate\Support\Facades\App::class,
        'Arr' => Illuminate\Support\Arr::class,
        'Artisan' => Illuminate\Support\Facades\Artisan::class,
        'Auth' => Illuminate\Support\Facades\Auth::class,
        'Blade' => Illuminate\Support\Facades\Blade::class,
        'Broadcast' => Illuminate\Support\Facades\Broadcast::class,
        'Bus' => Illuminate\Support\Facades\Bus::class,
        'Cache' => Illuminate\Support\Facades\Cache::class,
        'Config' => Illuminate\Support\Facades\Config::class,
        'Cookie' => Illuminate\Support\Facades\Cookie::class,
        'Crypt' => Illuminate\Support\Facades\Crypt::class,
        'DB' => Illuminate\Support\Facades\DB::class,
        'Eloquent' => Illuminate\Database\Eloquent\Model::class,
        'Event' => Illuminate\Support\Facades\Event::class,
        'File' => Illuminate\Support\Facades\File::class,
        'Gate' => Illuminate\Support\Facades\Gate::class,
        'Hash' => Illuminate\Support\Facades\Hash::class,
        'Lang' => Illuminate\Support\Facades\Lang::class,
        'Log' => Illuminate\Support\Facades\Log::class,
        'Mail' => Illuminate\Support\Facades\Mail::class,
        'Notification' => Illuminate\Support\Facades\Notification::class,
        'Password' => Illuminate\Support\Facades\Password::class,
        'Queue' => Illuminate\Support\Facades\Queue::class,
        'Redirect' => Illuminate\Support\Facades\Redirect::class,
        'Redis' => Illuminate\Support\Facades\Redis::class,
        'Request' => Illuminate\Support\Facades\Request::class,
        'Response' => Illuminate\Support\Facades\Response::class,
        'Route' => Illuminate\Support\Facades\Route::class,
        'Schema' => Illuminate\Support\Facades\Schema::class,
        'Session' => Illuminate\Support\Facades\Session::class,
        'Storage' => Illuminate\Support\Facades\Storage::class,
        'Str' => Illuminate\Support\Str::class,
        'URL'                   => Illuminate\Support\Facades\URL::class,
        'Validator'             => Illuminate\Support\Facades\Validator::class,
        'View'                  => Illuminate\Support\Facades\View::class,
        'Form'                  => Collective\Html\FormFacade::class,
        'Html'                  => Collective\Html\HtmlFacade::class,
        'Input'                 => Illuminate\Support\Facades\Request::class,
        'Socialize'             => Laravel\Socialite\Facades\Socialite::class,
        'Omnipay'               => Ignited\LaravelOmnipay\Facades\OmnipayFacade::class,
        'QrCode'                => SimpleSoftwareIO\QrCode\Facades\QrCode::class,
        'Helpers'               => App\Libraries\Helpers::class,
        'Image'                 => Intervention\Image\Facades\Image::class,
        'Captcha'               => Mews\Captcha\Facades\Captcha::class,
    ],

];
