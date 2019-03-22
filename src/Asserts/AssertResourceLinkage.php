<?php

namespace VGirol\JsonApiAssert\Laravel\Asserts;

use PHPUnit\Framework\Assert as PHPUnit;
use VGirol\JsonApiAssert\Assert as JsonApiAssert;

trait AssertResourceLinkage
{
    /**
     * Undocumented function
     *
     * @param Illuminate\Database\Eloquent\Model|null $model
     * @param string|null $resourceType
     * @param [type] $resLinkage
     * @return void
     */
    public static function assertSingleResourceLinkageEquals($model, $resourceType, $resLinkage)
    {
        if (is_null($model)) {
            PHPUnit::assertNull($resLinkage);
        } else {
            JsonApiAssert::assertIsValidResourceLinkage($resLinkage);
            JsonApiAssert::assertIsNotArrayOfObjects($resLinkage);
            static::assertResourceIdentifierObjectEqualsModel($model, $resourceType, $resLinkage);
        }
    }

    public static function assertResponseResourceLinkageListEqualsCollection($collection, $resourceType, $resLinkage)
    {
        if (is_null($collection) || (count($collection) == 0)) {
            PHPUnit::assertIsArray($resLinkage);
            PHPUnit::assertEmpty($resLinkage);

            return;
        }

        JsonApiAssert::assertIsArrayOfObjects($resLinkage);
        JsonApiAssert::assertIsValidResourceLinkage($resLinkage);

        PHPUnit::assertEquals(count($collection), count($resLinkage));

        $i = 0;
        foreach ($collection as $model) {
            static::assertSingleResourceLinkageEquals($model, $resourceType, $resLinkage[$i]);
            $i++;
        }
    }
}
