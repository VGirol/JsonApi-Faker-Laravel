<?php
namespace VGirol\JsonApiAssert\Asserts;

use PHPUnit\Framework\Assert as PHPUnit;
use VGirol\JsonApiAssert\Messages;

trait AssertJsonapiObject
{
    public static function assertIsValidJsonapiObject($jsonapi)
    {
        static::assertIsNotArrayOfObjects(
            $jsonapi,
            Messages::OBJECT_NOT_ARRAY
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
