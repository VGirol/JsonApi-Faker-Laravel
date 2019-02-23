<?php
namespace VGirol\JsonApiAssert\Asserts;

use VGirol\JsonApiAssert\Messages;

trait AssertMetaObject
{
    public static function assertIsValidMetaObject($meta)
    {
        static::assertIsNotArrayOfObjects(
            $meta,
            Messages::META_OBJECT_IS_NOT_ARRAY
        );

        foreach (array_keys($meta) as $key) {
            static::assertIsValidMemberName($key);
        }
    }
}
