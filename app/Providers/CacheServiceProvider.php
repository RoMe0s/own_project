<?php

namespace App\Providers;

use App\Services\CacheService;
use Illuminate\Support\ServiceProvider;

class CacheServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //CacheService::init('News', 'slug')->items()->get();
        dd(cache()->items()->get());
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(
            'cacheService',
            function () {
                return CacheService::init('News', 'slug');
            }
        );
    }
}
