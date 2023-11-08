<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

use Helpers;
use View;
use Auth;
use URL;
use App\Event;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        Paginator::useBootstrap();

        view()->composer('layouts._partials.events-navigation', function ($view) {
            $view->with('events', Event::orderBy('display_name', 'desc')->get());
        });
        view()->composer('*', function ($view) {
            $view->with('user', Auth::user());
        });

        // Pull API Keys
        if (env('ENV_OVERRIDE')) {
            // From ENV File
            // Paypal
            @\Config::set('laravel-omnipay.gateways.paypal_express.credentials.username', env('PAYPAL_USERNAME'));
            @\Config::set('laravel-omnipay.gateways.paypal_express.credentials.password', env('PAYPAL_PASSWORD'));
            @\Config::set('laravel-omnipay.gateways.paypal_express.credentials.signature', env('PAYPAL_SIGNATURE'));
            // Stripe
            @\Config::set('laravel-omnipay.gateways.stripe.credentials.public', env('STRIPE_PUBLIC_KEY'));
            @\Config::set('laravel-omnipay.gateways.stripe.credentials.secret', env('STRIPE_SECRET_KEY'));
            // Facebook
            @\Config::set('services.facebook.client_id', env('FACEBOOK_APP_ID'));
            @\Config::set('facebook.config.app_id', env('FACEBOOK_APP_ID'));
            @\Config::set('services.facebook.client_secret', env('FACEBOOK_APP_SECRET'));
            @\Config::set('facebook.config.app_secret',env('FACEBOOK_APP_SECRET'));
            // Challonge
            @\Config::set('challonge.api_key', env('CHALLONGE_API_KEY'));
            // Google Analytics
            @\Config::set('analytics.configurations.GoogleAnalytics.tracking_id', env('GOOGLE_ANALYTICS_TRACKING_ID', null));
            // Facebook Analytics
            @\Config::set('facebook-pixel.facebook_pixel_id', env('FACEBOOK_PIXEL_ID', null));
            // Steam
            @\Config::set('steam-auth.api_key', env('STEAM_API_KEY'));
        } elseif (\Schema::hasTable('api_keys')) {
            // From Database
            // Paypal
            @\Config::set('laravel-omnipay.gateways.paypal_express.credentials.username', \App\ApiKey::where('key', 'paypal_username')->first()->value);
            @\Config::set('laravel-omnipay.gateways.paypal_express.credentials.password', \App\ApiKey::where('key', 'paypal_password')->first()->value);
            @\Config::set('laravel-omnipay.gateways.paypal_express.credentials.signature', \App\ApiKey::where('key', 'paypal_signature')->first()->value);
            // Stripe
            @\Config::set('laravel-omnipay.gateways.stripe.credentials.public', \App\ApiKey::where('key', 'stripe_public_key')->first()->value);
            @\Config::set('laravel-omnipay.gateways.stripe.credentials.secret', \App\ApiKey::where('key', 'stripe_secret_key')->first()->value);
            // Facebook
            @\Config::set('services.facebook.client_id', \App\ApiKey::where('key', 'facebook_app_id')->first()->value);
            @\Config::set('facebook.config.app_id', \App\ApiKey::where('key', 'facebook_app_id')->first()->value);
            @\Config::set('services.facebook.client_secret', \App\ApiKey::where('key', 'facebook_app_id')->first()->value);
            @\Config::set('facebook.config.app_secret', \App\ApiKey::where('key', 'facebook_app_id')->first()->value);
            // Challonge
            @\Config::set('challonge.api_key', \App\ApiKey::where('key', 'challonge_api_key')->first()->value);
            // Google Analytics
            @\Config::set('analytics.configurations.GoogleAnalytics.tracking_id', \App\ApiKey::where('key', 'google_analytics_tracking_id')->first()->value);
            // Facebook Analytics
            @\Config::set('facebook-pixel.facebook_pixel_id', \App\ApiKey::where('key', 'facebook_pixel_id')->first()->value);
            // Steam
            @\Config::set('steam-auth.api_key', \App\ApiKey::where('key', 'steam_api_key')->first()->value);
        }

        // Google Analytics Cannot accept 'null' fix
        if (config('analytics.configurations.GoogleAnalytics.tracking_id') == null) {
            @\Config::set('analytics.configurations.GoogleAnalytics.tracking_id', '');
        }

        // Facebook Analyics Enabled fox
        @\Config::set('facebook-pixel.enabled', true);
        if (config('facebook-pixel.facebook_pixel_id') == null) {
            @\Config::set('facebook-pixel.enabled', false);
        }

        if (\Schema::hasTable('settings')) {
            foreach (\App\Setting::all() as $setting) {
                @\Config::set('settings.'.$setting->setting, $setting->value);
            }
        }

        // Set SEO Defaults
        @\Config::set('seotools.meta.defaults.description', Helpers::getSeoDescription());
        if (config('settings.seo_keywords') != null) {
            @\Config::set('seotools.meta.defaults.keywords', Helpers::getSeoKeywords());
        }
        @\Config::set('seotools.opengraph.defaults.description', Helpers::getSeoDescription());
        @\Config::set('seotools.opengraph.defaults.site_name', config('settings.org_name'));

        // Foce HTTPS if required
        if (env('ENABLE_HTTPS') || env('FORCE_APP_HTTPS')) {
            URL::forceScheme('https');
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if (config('app.debug') === true) {
            $this->app->register(\KitLoong\MigrationsGenerator\MigrationsGeneratorServiceProvider::class);
            $this->app->register(\Barryvdh\Debugbar\ServiceProvider::class);
            $this->app->register(\Orangehill\Iseed\IseedServiceProvider::class);
        }
    }
}
