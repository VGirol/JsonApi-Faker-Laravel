<?php
namespace VGirol\JsonApi\Tests;

trait JsonApiTestCommon
{
    protected $resourceClass = \VGirol\JsonApi\Resources\JsonApiResource::class;
    protected $resourceCollectionClass = \VGirol\JsonApi\Resources\JsonApiResourceCollection::class;

    protected function setUp()
    {
        parent::setUp();

        $helpersPath = __DIR__.'/tools/helpers/helpers.php';
        require_once($helpersPath);
    }

    protected function setModel() : string
    {
        return \VGirol\JsonApi\Tests\Tools\Models\ModelForTest::class;
    }

    protected function setEndpoint() : string
    {
        return 'jsonapitest';
    }
}
