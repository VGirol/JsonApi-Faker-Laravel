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
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/jsonapi-assert.php' => config_path('jsonapi-assert.php'),
            ], 'config');
        }

        $this->mergeConfigFrom(
            __DIR__ . '/../config/jsonapi-assert.php',
            'jsonapi-assert'
        );

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
