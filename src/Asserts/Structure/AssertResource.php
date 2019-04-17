<?php

namespace VGirol\JsonApiAssert\Laravel\Asserts\Structure;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use PHPUnit\Framework\Assert as PHPUnit;
use PHPUnit\Util\InvalidArgumentHelper;

trait AssertResource
{
    /**
     * Asserts that a resource object correspond to a given model.
     *
     * @param Illuminate\Database\Eloquent\Model $expectedModel
     * @param string $expectedResourceType
     * @param array $resource
     */
    public static function assertResourceObjectEquals($expectedModel, $expectedResourceType, $resource)
    {
        if (!\is_a($expectedModel, Model::class)) {
            throw InvalidArgumentHelper::factory(
                1,
                Model::class,
                var_export($expectedModel, true)
            );
        }

        static::assertResourceIdentifierEquals($expectedModel->getKey(), $expectedResourceType, $resource);
        static::assertHasAttributes($resource);
        PHPUnit::assertEquals(
            $expectedModel->getAttributes(),
            $resource['attributes']
        );
    }

    /**
     * Asserts that an array of resource objects correspond to a given collection.
     *
     * @param Illuminate\Database\Eloquent\Collection $expectedCollection
     * @param string $expectedResourceType
     * @param array $collection
     */
    public static function assertResourceCollectionEquals($expectedCollection, $expectedResourceType, $collection)
    {
        if (!\is_a($expectedCollection, Collection::class)) {
            throw InvalidArgumentHelper::factory(
                1,
                Collection::class,
                null
            );
        }

        static::assertIsArrayOfObjects($collection);
        PHPUnit::assertEquals(count($expectedCollection), count($collection));

        $i = 0;
        foreach ($expectedCollection as $model) {
            static::assertResourceObjectEquals($model, $expectedResourceType, $collection[$i]);
            $i++;
        }
    }
}
