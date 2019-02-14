<?php

namespace VGirol\JsonApi;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use VGirol\JsonApi\Commands\JsonApiMakeCommand;
use VGirol\JsonApi\Middleware\JsonApiAddHeader;
use VGirol\JsonApi\Middleware\JsonApiCheckHeader;
use VGirol\JsonApi\Commands\JsonApiModelMakeCommand;
use VGirol\JsonApi\Commands\JsonApiRequestMakeCommand;
use VGirol\JsonApi\Commands\JsonApiResourceMakeCommand;
use VGirol\JsonApi\Commands\JsonApiControllerMakeCommand;

class JsonApiServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $router = $this->app['router'];
        $router->pushMiddlewareToGroup('api', JsonApiCheckHeader::class);
        $router->pushMiddlewareToGroup('api', JsonApiAddHeader::class);

        if ($this->app->runningInConsole()) {
            $this->commands([
                JsonApiResourceMakeCommand::class,
                JsonApiRequestMakeCommand::class,
                JsonApiModelMakeCommand::class,
                JsonApiMakeCommand::class,
                JsonApiControllerMakeCommand::class
            ]);
        }

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/config/jsonapi.php' => config_path('jsonapi.php'),
            ], 'config');
        }
        $this->mergeConfigFrom(
            __DIR__ . '/config/jsonapi.php',
            'jsonapi'
        );

        Route::macro('jsonApiResource', function (string $name, string $controller, array $options = []) {
            if (!isset($options['parameters'])) {
                $options['parameters'] = [$name => 'id'];
            }

            Route::apiResource($name, $controller, $options);

            if (isset($options['relationships']) && ($options['relationships'] === true)) {
                Route::jsonApiRelationshipsResource($name, $controller);
            }
        });

        Route::macro('jsonApiRelationshipsResource', function (string $name, string $controller) {
            Route::name("{$name}.relationships")->get("{$name}/{id}/relationships/{relationship}", "{$controller}@relationships");
            Route::name("{$name}.relationships.update")->match(['put', 'patch'], "{$name}/{id}/relationships/{relationship}", "{$controller}@updateRelationships");
            Route::name("{$name}.related.show")->get("{$name}/{id}/{relationship}", "{$controller}@showRelated");
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {

    }
}
