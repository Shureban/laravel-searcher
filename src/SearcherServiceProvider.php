<?php

namespace Shureban\LaravelSearcher;

use Illuminate\Support\ServiceProvider;

class SearcherServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([__DIR__ . '/../config' => base_path('config')]);
    }
}
