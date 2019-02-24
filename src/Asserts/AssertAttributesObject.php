<?php
namespace VGirol\JsonApiAssert\Asserts;

use VGirol\JsonApiAssert\Messages;

trait AssertAttributesObject
{
    public static function assertIsValidAttributesObject($attributes)
    {
        static::assertIsNotArrayOfObjects(
            $attributes,
            Messages::ATTRIBUTES_OBJECT_IS_NOT_ARRAY
        );

        static::assertFieldHasNoForbiddenMemberName($attributes);

        foreach ($attributes as $key => $value) {
            static::assertIsValidMemberName($key);
        }
    }
}
