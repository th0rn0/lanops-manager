<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

use View;
use Auth;
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
        view()->composer('layouts._partials.events-navigation', function ($view) {
            $view->with('events', Event::orderBy('display_name', 'desc')->get());
        });
        view()->composer('*', function ($view) {
            $view->with('user', Auth::user());
        });

        Paginator::useBootstrap();

        // Pull API Keys
        // TODO - move these to config files?
        // if (env('ENV_OVERRIDE')) {
            // From ENV File
            // Paypal
            @\Config::set('laravel-omnipay.gateways.paypal_express.credentials.username', env('PAYPAL_USERNAME'));
            @\Config::set('laravel-omnipay.gateways.paypal_express.credentials.password', env('PAYPAL_PASSWORD'));
            @\Config::set('laravel-omnipay.gateways.paypal_express.credentials.signature', env('PAYPAL_SIGNATURE'));
            // Stripe
            @\Config::set('laravel-omnipay.gateways.stripe.credentials.public', env('STRIPE_SECRET_KEY'));
            @\Config::set('laravel-omnipay.gateways.stripe.credentials.secret', env('STRIPE_PUBLIC_KEY'));
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
        // }
       
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
        // @\Config::set('seotools.meta.defaults.description', config('settings.org_tagline'));
        // if (config('settings.seo_keywords') != null) {
        //     @\Config::set('seotools.meta.defaults.keywords', explode(',',config('settings.seo_keywords')));
        // }
        // @\Config::set('seotools.opengraph.defaults.description', config('settings.org_tagline'));
        // @\Config::set('seotools.opengraph.defaults.site_name', config('settings.org_name'));
        
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if (config('app.debug') === true) {
            $this->app->register(\Barryvdh\Debugbar\ServiceProvider::class);
            $this->app->register(\Orangehill\Iseed\IseedServiceProvider::class);
        }
    }
}
