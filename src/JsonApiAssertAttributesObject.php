<?php
namespace VGirol\JsonApiAssert;

use PHPUnit\Framework\Assert as PHPUnit;
use VGirol\JsonApiAssert\JsonApiAssertMessages;

trait JsonApiAssertAttributesObject
{
    public static function assertIsValidAttributesObject($attributes)
    {
        static::assertIsNotArrayOfObjects(
            $attributes,
            JsonApiAssertMessages::JSONAPI_ERROR_ATTRIBUTES_OBJECT_IS_NOT_ARRAY
        );

        static::assertFieldHasNoForbiddenMemberName($attributes);

        foreach ($attributes as $key => $value) {
            static::assertIsValidMemberName($key);
        }
    }
}
