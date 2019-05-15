<?php
namespace VGirol\JsonApiAssert\Laravel\Asserts\Content;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use VGirol\JsonApiAssert\Laravel\Factory\HelperFactory;

trait AssertResourceLinkage
{
    /**
     * Asserts that a resource object correspond to a given model.
     *
     * @param Model $expected
     * @param string $resourceType
     * @param array $json
     */
    public static function assertResourceIdentifierEqualsModel($expected, $resourceType, $json)
    {
        $expected = HelperFactory::create('resource-identifier', $expected, $resourceType)->toArray();

        static::assertResourceIdentifierEquals($expected, $json);
    }

    /**
     * Asserts that an array of resource objects correspond to a given collection.
     *
     * @param Collection $collection
     * @param string $resourceType
     * @param array $json
     */
    public static function assertResourceCollectionEqualsCollection($collection, $resourceType, $json)
    {
        $expected = HelperFactory::create('collection', $collection, $resourceType, false)->toArray();

        static::assertResourceCollectionEquals($expected, $json);
    }
}
