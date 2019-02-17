<?php
namespace VGirol\JsonApiAssert;

use PHPUnit\Framework\Assert as PHPUnit;
use VGirol\JsonApiAssert\JsonApiAssertMessages;

trait JsonApiAssertJsonapiObject
{
    public static function assertIsValidJsonapiObject($jsonapi)
    {
        static::assertIsNotArrayOfObjects(
            $jsonapi,
            JsonApiAssertMessages::JSONAPI_ERROR_JSONAPI_OBJECT_NOT_ARRAY
        );

        $allowed = ['version', 'meta'];
        static::assertContainsOnlyAllowedMembers(
            $allowed,
            $jsonapi
        );

        if (isset($jsonapi['version'])) {
            PHPUnit::assertIsString($jsonapi['version']);
        }

        if (isset($jsonapi['meta'])) {
            static::assertIsValidMetaObject($jsonapi['meta']);
        }
    }
}
