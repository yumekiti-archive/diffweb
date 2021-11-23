<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Session;
use Inertia\Inertia;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        Inertia::share('flash', function(){
            return [
                'success' => Session::get('success'),
                'error' => Session::get('error')
            ];
        });
    }
}
