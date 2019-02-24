<?php

namespace VGirol\JsonApiAssert\Laravel\Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;
use VGirol\JsonApiAssert\Laravel\JsonApiAssertServiceProvider;

abstract class TestCase extends BaseTestCase
{
    protected $mediaType = 'application/vnd.api+json';

    /**
     * Load package service provider
     * @param  \Illuminate\Foundation\Application $app
     * @return VGirol\JsonApiAssert\Laravel\JsonApiAssertServiceProvider
     */
    protected function getPackageProviders($app)
    {
        return [JsonApiAssertServiceProvider::class];
    }
}
