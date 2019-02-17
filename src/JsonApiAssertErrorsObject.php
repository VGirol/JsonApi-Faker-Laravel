<?php
namespace VGirol\JsonApiAssert;

use PHPUnit\Framework\Assert as PHPUnit;
use VGirol\JsonApiAssert\JsonApiAssertMessages;

trait JsonApiAssertErrorsObject
{
    public static function assertIsValidErrorsObject($errors)
    {
        static::assertIsArrayOfObjects(
            $errors,
            JsonApiAssertMessages::JSONAPI_ERROR_ERRORS_OBJECT_NOT_ARRAY
        );

        foreach ($errors as $error) {
            static::assertIsValidErrorObject($error);
        }
    }

    public static function assertIsValidErrorObject($error)
    {
        PHPUnit::assertIsArray(
            $error,
            JsonApiAssertMessages::JSONAPI_ERROR_ERROR_OBJECT_NOT_ARRAY
        );

        PHPUnit::assertNotEmpty(
            $error,
            JsonApiAssertMessages::JSONAPI_ERROR_ERROR_OBJECT_NOT_EMPTY
        );

        $allowed = ['id', 'links', 'status', 'code', 'title', 'details', 'source', 'meta'];
        static::assertContainsOnlyAllowedMembers(
            $allowed,
            $error
        );

        if (isset($error['status'])) {
            PHPUnit::assertIsString(
                $error['status'],
                JsonApiAssertMessages::JSONAPI_ERROR_ERROR_STATUS_IS_NOT_STRING
            );
        }

        if (isset($error['code'])) {
            PHPUnit::assertIsString(
                $error['code'],
                JsonApiAssertMessages::JSONAPI_ERROR_ERROR_CODE_IS_NOT_STRING
            );
        }

        if (isset($error['title'])) {
            PHPUnit::assertIsString(
                $error['title'],
                JsonApiAssertMessages::JSONAPI_ERROR_ERROR_TITLE_IS_NOT_STRING
            );
        }

        if (isset($error['details'])) {
            PHPUnit::assertIsString(
                $error['details'],
                JsonApiAssertMessages::JSONAPI_ERROR_ERROR_DETAILS_IS_NOT_STRING
            );
        }

        if (isset($error['source'])) {
            static::assertIsValidErrorSourceObject($error['source']);
        }

        if (isset($error['links'])) {
            static::assertIsValidErrorLinksObject($error['links']);
        }

        if (isset($error['meta'])) {
            static::assertIsValidMetaObject($error['meta']);
        }
    }

    public static function assertIsValidErrorLinksObject($links)
    {
        $allowed = ['about'];
        static::assertIsValidLinksObject($links, $allowed);
    }

    public static function assertIsValidErrorSourceObject($source)
    {
        PHPUnit::assertIsArray(
            $source,
            JsonApiAssertMessages::JSONAPI_ERROR_ERROR_SOURCE_OBJECT_NOT_ARRAY
        );

        // foreach (array_keys($source) as $name) {
        //     static::assertIsValidMemberName($name);
        //     static::assertIsNotForbiddenMemberName($name);
        // }

        if (isset($source['pointer'])) {
            PHPUnit::assertIsString(
                $source['pointer'],
                JsonApiAssertMessages::JSONAPI_ERROR_ERROR_SOURCE_POINTER_IS_NOT_STRING
            );
            PHPUnit::assertStringStartsWith(
                '/',
                $source['pointer'],
                JsonApiAssertMessages::JSONAPI_ERROR_ERROR_SOURCE_POINTER_START
            );
        }

        if (isset($source['parameter'])) {
            PHPUnit::assertIsString(
                $source['parameter'],
                JsonApiAssertMessages::JSONAPI_ERROR_ERROR_SOURCE_PARAMETER_IS_NOT_STRING
            );
        }
    }
}
