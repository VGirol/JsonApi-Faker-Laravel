<?php
namespace VGirol\JsonApiAssert\Laravel\Asserts\Content;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use VGirol\JsonApiAssert\Laravel\Factory\HelperFactory;

trait AssertResource
{
    /**
     * Asserts that a resource object correspond to a given model.
     *
     * @param Model $expected
     * @param string $resourceType
     * @param array $json
     */
    public static function assertResourceObjectEqualsModel($expected, $resourceType, $json)
    {
        $expected = HelperFactory::create('resource-object', $expected, $resourceType)->toArray();

        static::assertResourceObjectEquals($expected, $json);
    }

    /**
     * Asserts that an array of resource objects correspond to a given collection.
     *
     * @param Collection $expected
     * @param string $resourceType
     * @param array $json
     */
    public static function assertResourceIdentifierCollectionEqualsCollection($expected, $resourceType, $json)
    {
        $expected = HelperFactory::create('collection', $expected, $resourceType, true)->toArray();

        static::assertResourceIdentifierCollectionEquals($expected, $json);
    }
}
