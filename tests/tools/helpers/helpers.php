<?php

use Illuminate\Database\Eloquent\Factory as EloquentFactory;

if (! function_exists('factoryJsonapi')) {
    /**
     * Create a model factory builder for a given class, name, and amount.
     *
     * @param  dynamic  class|class,name|class,amount|class,name,amount
     * @return \Illuminate\Database\Eloquent\FactoryBuilder
     */
    function factoryJsonapi()
    {
        $factory = app(EloquentFactory::class);
        $factory->load(base_path('packages/vgirol/jsonapi/tests/tools/factories'));

        $arguments = func_get_args();

        if (isset($arguments[1]) && is_string($arguments[1])) {
            return $factory->of($arguments[0], $arguments[1])->times($arguments[2] ?? null);
        } elseif (isset($arguments[1])) {
            return $factory->of($arguments[0])->times($arguments[1]);
        }

        return $factory->of($arguments[0]);
    }
}
