<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
        //
        //View::share('events', Event::all());
        view()->composer('layouts._partials.events-navigation', function($view){
            $view->with('events', Event::orderBy('display_name', 'desc')->get());
        });
        view()->composer('*', function($view){
          $view->with('user', Auth::user());
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if (config('app.debug') === true) {
            $this->app->register(\Way\Generators\GeneratorsServiceProvider::class);
            $this->app->register(\Barryvdh\Debugbar\ServiceProvider::class);
            $this->app->register(\Orangehill\Iseed\IseedServiceProvider::class);
        }
    }
}
