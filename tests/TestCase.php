<?php

namespace VGirol\JsonApi\Tests;

//use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use VGirol\JsonApi\Tools\ClassNameTools;
use VGirol\JsonApi\Tools\Assert\JsonApiAssert;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use JsonApiAssert;
    use ClassNameTools;

    /**
     * The endpoint to query in the API
     * e.g = /api/<endpoint>
     *
     * @var string
     */
    protected $endpoint = 'jsonapitest';

    protected $routeName = 'jsonapitest';

    protected function setUp()
    {
        parent::setUp();

        $helpersPath = __DIR__ . '/tools/helpers/helpers.php';
        require_once($helpersPath);
    }

    protected function getDictionary()
    {
        return [
            'primary' => [
                'model' => \VGirol\JsonApi\Tests\Tools\Models\ModelForTest::class,
                'resource' => \VGirol\JsonApi\Tests\Tools\Resources\ModelForTestResource::class,
                'resource-collection' => \VGirol\JsonApi\Tests\Tools\Resources\ModelForTestResourceCollection::class,
            ],
            'relationships' => [
                'related' => [
                    'model' => \VGirol\JsonApi\Tests\Tools\Models\RelatedModelForTest::class,
                    'resource' => \VGirol\JsonApi\Tests\Tools\Resources\RelatedModelForTestResource::class,
                    'resource-collection' => \VGirol\JsonApi\Tests\Tools\Resources\RelatedModelForTestResourceCollection::class,
                ]
            ]
        ];
    }
}
