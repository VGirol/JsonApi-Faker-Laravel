<?php

namespace VGirol\JsonApiAssert\Laravel\Asserts;

use PHPUnit\Framework\Assert as PHPUnit;
use VGirol\JsonApiAssert\Assert as JsonApiAssert;

trait AssertResource
{
    /**
     * Undocumented function
     *
     * @param Illuminate\Database\Eloquent\Model $expectedModel
     * @param string $expectedResourceType
     * @param array $resource
     * @return void
     */
    public static function assertResourceIdentifierObjectEqualsModel($expectedModel, $expectedResourceType, $resource)
    {
        JsonApiAssert::assertIsNotArrayOfObjects($resource);

        PHPUnit::assertEquals(
            $expectedResourceType,
            $resource['type']
        );

        PHPUnit::assertEquals(
            $expectedModel->getKey(),
            $resource['id']
        );
    }

    /**
     * Undocumented function
     *
     * @param Illuminate\Database\Eloquent\Model $expectedModel
     * @param string $expectedResourceType
     * @param array $resource
     * @return void
     */
    public static function assertResourceObjectEqualsModel($expectedModel, $expectedResourceType, $resource)
    {
        static::assertResourceIdentifierObjectEqualsModel($expectedModel, $expectedResourceType, $resource);

        JsonApiAssert::assertHasAttributes($resource);
        PHPUnit::assertEquals(
            $expectedModel->getAttributes(),
            $resource['attributes']
        );
    }

    /**
     * Undocumented function
     *
     * @param Illuminate\Database\Eloquent\Collection $expectedCollection
     * @param string $expectedResourceType
     * @param array $collection
     * @return void
     */
    public static function assertResourceObjectListEqualsCollection($expectedCollection, $expectedResourceType, $collection)
    {
        JsonApiAssert::assertIsArrayOfObjects($collection);
        PHPUnit::assertEquals(count($expectedCollection), count($collection));

        $i = 0;
        foreach ($expectedCollection as $model) {
            static::assertResourceObjectEqualsModel($model, $expectedResourceType, $collection[$i]);
            $i++;
        }
    }
}
