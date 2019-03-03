<?php

namespace VGirol\JsonApiAssert\Laravel;

use Illuminate\Support\ServiceProvider;

class JsonApiAssertServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        foreach (glob(__DIR__ . '/macro/*.php') as $file) {
            require_once($file);
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    { }
}
