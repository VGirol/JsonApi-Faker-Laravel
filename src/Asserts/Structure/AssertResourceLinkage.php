<?php

namespace VGirol\JsonApiAssert\Laravel\Asserts\Structure;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use PHPUnit\Framework\Assert as PHPUnit;
use PHPUnit\Util\InvalidArgumentHelper;

trait AssertResourceLinkage
{
    /**
     * Asserts that a resource identifier object has "id" and "type" member equal to the given parameters.
     *
     * @param integer|string $expectedId
     * @param string $expectedResourceType
     * @param array $resource
     */
    public static function assertResourceIdentifierEquals($expectedId, $expectedResourceType, $resource)
    {
        PHPUnit::assertEquals(
            $expectedResourceType,
            $resource['type']
        );

        PHPUnit::assertEquals(
            $expectedId,
            $resource['id']
        );
    }

    /**
     * Asserts that an array of resource identifer objects correspond to a given collection.
     *
     * @param Illuminate\Database\Eloquent\Collection $refCollection
     * @param string $resourceType
     * @param array $collection
     */
    public static function assertResourceIdentifierCollectionEquals($refCollection, $resourceType, $collection)
    {
        if (!\is_a($refCollection, Collection::class)) {
            throw InvalidArgumentHelper::factory(
                1,
                Collection::class,
                null
            );
        }

        static::assertIsArrayOfObjects($collection);
        PHPUnit::assertEquals(count($refCollection), count($collection));

        $i = 0;
        foreach ($refCollection as $model) {
            static::assertResourceIdentifierEquals($model->getKey(), $resourceType, $collection[$i]);
            $i++;
        }
    }

    /**
     * Asserts that a resource linkage object correspond to a given reference object
     * which can be either the null value, a single resource identifier object,
     * an empty collection or a collection of resource identifier ojects.
     *
     * @param Illuminate\Database\Eloquent\Collection|Illuminate\Database\Eloquent\Model|null $reference
     * @param string|null $resourceType
     * @param array|null $resLinkage
     * @param boolean   $strict         If true, unsafe characters are not allowed when checking members name.
     */
    public static function assertResourceLinkageEquals($reference, $resourceType, $resLinkage, $strict)
    {
        static::assertIsValidResourceLinkage($resLinkage, $strict);

        if (is_null($reference)) {
            PHPUnit::assertNull($resLinkage);

            return;
        }

        if (is_a($reference, Model::class)) {
            static::assertIsNotArrayOfObjects($resLinkage);
            static::assertResourceIdentifierEquals($reference->getKey(), $resourceType, $resLinkage);

            return;
        }

        if (count($reference) == 0) {
            PHPUnit::assertEmpty($resLinkage);

            return;
        }

        static::assertResourceIdentifierCollectionEquals($reference, $resourceType, $resLinkage);
    }
}
