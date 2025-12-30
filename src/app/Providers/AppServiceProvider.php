<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

use Auth;
use App\Models\Event;

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
            $view->with('events', Event::orderByDesc('start')->whereNot('status', 'DRAFT')->where("start", ">", \Carbon\Carbon::today())->get());
        });
        view()->composer('*', function ($view) {
            $view->with('user', Auth::user());
        });

        Paginator::useBootstrap();

        if (env('STRIPE_SECRET_KEY') != '' && env('STRIPE_PUBLIC_KEY') != '') {
            @\Config::set('laravel-omnipay.gateways.available_payment_gateways', ['stripe']);
        }
        
        if (env('PAYPAL_USERNAME') != '' && env('PAYPAL_PASSWORD') != '' && env('PAYPAL_SIGNATURE') != '') {
            @\Config::set(
                'laravel-omnipay.gateways.available_payment_gateways', 
                array_merge(
                    config(
                        'laravel-omnipay.gateways.available_payment_gateways'
                    ), 
                    ['paypal_express']
                )
            );
        }

        seo()
            ->site(config('app.name'))
            ->description(default:  config('app.seo_description'))
            ->image(default: fn () => config('app.url') . config('app.logo'))
            ->twitterSite('@LanOps');
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
