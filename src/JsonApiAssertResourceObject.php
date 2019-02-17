<?php
namespace VGirol\JsonApiAssert;

use PHPUnit\Framework\Assert as PHPUnit;
use VGirol\JsonApiAssert\JsonApiAssertMessages;

trait JsonApiAssertResourceObject
{
    public static function assertIsValidResourceObject($resource)
    {
        static::assertResourceObjectHasValidTopLevelStructure($resource);
        static::assertResourceIdMember($resource);
        static::assertResourceTypeMember($resource);

        if (isset($resource['attributes'])) {
            static::assertIsValidAttributesObject($resource['attributes']);
        }

        if (isset($resource['relationships'])) {
            static::assertIsValidRelationshipsObject($resource['relationships']);
        }

        if (isset($resource['links'])) {
            static::assertIsValidResourceLinksObject($resource['links']);
        }

        if (isset($resource['meta'])) {
            static::assertIsValidMetaObject($resource['meta']);
        }

        static::assertValidFields($resource);
    }

    public static function assertResourceObjectHasValidTopLevelStructure($resource)
    {
        PHPUnit::assertIsArray(
            $resource,
            JsonApiAssertMessages::JSONAPI_ERROR_RESOURCE_IS_NOT_ARRAY
        );

        PHPUnit::assertArrayHasKey(
            'id',
            $resource,
            JsonApiAssertMessages::JSONAPI_ERROR_RESOURCE_ID_MEMBER_IS_ABSENT
        );

        PHPUnit::assertArrayHasKey(
            'type',
            $resource,
            JsonApiAssertMessages::JSONAPI_ERROR_RESOURCE_TYPE_MEMBER_IS_ABSENT
        );

        static::assertContainsAtLeastOneMember(['attributes', 'relationships', 'links', 'meta'], $resource);

        $allowed = ['id', 'type', 'meta', 'attributes', 'links', 'relationships'];
        static::assertContainsOnlyAllowedMembers($allowed, $resource);
    }

    public static function assertResourceIdMember($resource)
    {
        PHPUnit::assertNotEmpty(
            $resource['id'],
            JsonApiAssertMessages::JSONAPI_ERROR_RESOURCE_ID_MEMBER_IS_EMPTY
        );

        PHPUnit::assertIsString(
            $resource['id'],
            JsonApiAssertMessages::JSONAPI_ERROR_RESOURCE_ID_MEMBER_IS_NOT_STRING
        );
    }

    public static function assertResourceTypeMember($resource)
    {
        PHPUnit::assertNotEmpty(
            $resource['type'],
            JsonApiAssertMessages::JSONAPI_ERROR_RESOURCE_TYPE_MEMBER_IS_EMPTY
        );

        PHPUnit::assertIsString(
            $resource['type'],
            JsonApiAssertMessages::JSONAPI_ERROR_RESOURCE_TYPE_MEMBER_IS_NOT_STRING
        );

        static::assertIsValidMemberName($resource['type']);
    }

    public static function assertIsValidResourceLinksObject($data)
    {
        $allowed = ['self'];
        static::assertIsValidLinksObject($data, $allowed);
    }

    public static function assertIsValidResourceIdentifierObject($resource)
    {
        PHPUnit::assertIsArray(
            $resource,
            JsonApiAssertMessages::JSONAPI_ERROR_RESOURCE_IDENTIFIER_IS_NOT_ARRAY
        );

        PHPUnit::assertArrayHasKey(
            'id',
            $resource,
            JsonApiAssertMessages::JSONAPI_ERROR_RESOURCE_ID_MEMBER_IS_ABSENT
        );
        static::assertResourceIdMember($resource);

        PHPUnit::assertArrayHasKey(
            'type',
            $resource,
            JsonApiAssertMessages::JSONAPI_ERROR_RESOURCE_TYPE_MEMBER_IS_ABSENT
        );
        static::assertResourceTypeMember($resource);

        $allowed = ['id', 'type', 'meta'];
        static::assertContainsOnlyAllowedMembers($allowed, $resource);

        if (isset($resource['meta'])) {
            static::assertIsValidMetaObject($resource['meta']);
        }
    }

    public static function assertValidFields($resource)
    {
        $bHasAttributes = false;
        if (isset($resource['attributes'])) {
            $bHasAttributes = true;
            foreach (array_keys($resource['attributes']) as $name) {
                static::assertIsNotForbiddenFieldName($name);
            }
        }

        if (isset($resource['relationships'])) {
            foreach (array_keys($resource['relationships']) as $name) {
                static::assertIsNotForbiddenFieldName($name);

                if ($bHasAttributes) {
                    PHPUnit::assertArrayNotHasKey(
                        $name,
                        $resource['attributes'],
                        JsonApiAssertMessages::JSONAPI_ERROR_FIELDS_HAVE_SAME_NAME
                    );
                }
            }
        }
    }

    public static function assertIsNotForbiddenFieldName($name)
    {
        $forbidden = ['type', 'id'];
        PHPUnit::assertNotContains(
            $name,
            $forbidden,
            JsonApiAssertMessages::JSONAPI_ERROR_FIELDS_NAME_NOT_ALLOWED
        );
    }
}
