<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Facebook\Facebook;

class FacebookServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Facebook::class, function ($app) {
            return new Facebook(config('facebook.config'));
        });
    }
}
