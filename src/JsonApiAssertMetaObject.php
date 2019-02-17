<?php
namespace VGirol\JsonApiAssert;

use PHPUnit\Framework\Assert as PHPUnit;
use VGirol\JsonApiAssert\JsonApiAssertMessages;

trait JsonApiAssertMetaObject
{
    public static function assertIsValidMetaObject($meta)
    {
        static::assertIsNotArrayOfObjects(
            $meta,
            JsonApiAssertMessages::JSONAPI_ERROR_META_OBJECT_IS_NOT_ARRAY
        );

        foreach (array_keys($meta) as $key) {
            static::assertIsValidMemberName($key);
        }
    }
}
