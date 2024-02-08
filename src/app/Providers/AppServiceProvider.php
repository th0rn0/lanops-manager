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
            // Paypal
            @\Config::set('laravel-omnipay.gateways.paypal_express.credentials.username', env('PAYPAL_USERNAME'));
            @\Config::set('laravel-omnipay.gateways.paypal_express.credentials.password', env('PAYPAL_PASSWORD'));
            @\Config::set('laravel-omnipay.gateways.paypal_express.credentials.signature', env('PAYPAL_SIGNATURE'));
            // Stripe
            @\Config::set('laravel-omnipay.gateways.stripe.credentials.public', env('STRIPE_SECRET_KEY'));
            @\Config::set('laravel-omnipay.gateways.stripe.credentials.secret', env('STRIPE_PUBLIC_KEY'));
            // Steam
            // TODO - MAKE SURE THIS IS SET IN THE ENTRYPOINT
            // @\Config::set('steam-auth.api_key', env('STEAM_API_KEY'));
        // }
       
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
        }
    }
}
