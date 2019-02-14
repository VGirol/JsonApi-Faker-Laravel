<?php
namespace VGirol\JsonApi\Tools\Assert;

use PHPUnit\Framework\Assert as PHPUnit;
use VGirol\JsonApi\Tools\Assert\JsonApiAssertMessages;

trait JsonApiAssertObject
{
    public static function assertIsValidMemberName($name)
    {
        PHPUnit::assertIsString(
            $name,
            JsonApiAssertMessages::JSONAPI_ERROR_MEMBER_NAME_IS_NOT_STRING
        );

        PHPUnit::assertGreaterThanOrEqual(
            1,
            strlen($name),
            JsonApiAssertMessages::JSONAPI_ERROR_MEMBER_NAME_IS_TOO_SHORT
        );

        // Globally allowed characters
        $globally = '\x{0030}-\x{0039}\x{0041}-\x{005A}\x{0061}-\x{007A}';
        $globallyNotSafe = '\x{0080}-\x{FFFF}';
        // Allowed characters
        $allowed = '\x{002D}\x{005F}';
        $allowedNotSafe = '\x{0020}';

        PHPUnit::assertNotRegExp(
            "/[^{$globally}{$globallyNotSafe}{$allowed}{$allowedNotSafe}]+/u",
            $name,
            JsonApiAssertMessages::JSONAPI_ERROR_MEMBER_NAME_HAVE_RESERVED_CHARACTERS
        );

        PHPUnit::assertRegExp(
            "/^[{$globally}{$globallyNotSafe}]{1}.*[{$globally}{$globallyNotSafe}]{1}$/u",
            $name,
            JsonApiAssertMessages::JSONAPI_ERROR_MEMBER_NAME_START_AND_END_WITH_ALLOWED_CHARACTERS
        );
    }

    public static function assertIsValidField($field)
    {
        if (!is_array($field)) {
            return;
        }

        foreach ($field as $key => $value) {
            // For objects, $key is a string
            // For arrays of objects, $key is an integer
            if (is_string($key)) {
                static::assertNoForbiddenMemberName($key);
            }
            static::assertIsValidField($value);
        }
    }

    public static function assertNoForbiddenMemberName($name)
    {
        $forbidden = ['relationships', 'links'];
        PHPUnit::assertNotContains(
            $name,
            $forbidden,
            JsonApiAssertMessages::JSONAPI_ERROR_MEMBER_NAME_NOT_ALLOWED
        );
    }

    public static function assertResourceHasIdMember($resource)
    {
        PHPUnit::assertArrayHasKey(
            'id',
            $resource,
            JsonApiAssertMessages::JSONAPI_ERROR_RESOURCE_ID_MEMBER_IS_ABSENT
        );

        PHPUnit::assertNotEmpty(
            $resource['id'],
            JsonApiAssertMessages::JSONAPI_ERROR_RESOURCE_ID_MEMBER_IS_EMPTY
        );

        PHPUnit::assertIsString(
            $resource['id'],
            JsonApiAssertMessages::JSONAPI_ERROR_RESOURCE_ID_MEMBER_IS_NOT_STRING
        );
    }

    public static function assertResourceHasTypeMember($resource)
    {
        PHPUnit::assertArrayHasKey(
            'type',
            $resource,
            JsonApiAssertMessages::JSONAPI_ERROR_RESOURCE_TYPE_MEMBER_IS_ABSENT
        );

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

    public static function assertIsValidResourceIdentifierObject($resource)
    {
        PHPUnit::assertIsArray(
            $resource,
            JsonApiAssertMessages::JSONAPI_ERROR_RESOURCE_IDENTIFIER_IS_NOT_ARRAY
        );

        static::assertResourceHasIdMember($resource);
        static::assertResourceHasTypeMember($resource);

        $allowed = ['id', 'type', 'meta'];
        static::assertContainsOnlyAllowedMembers($allowed, $resource);

        if (isset($resource['meta'])) {
            static::assertIsValidMetaObject($resource['meta']);
        }
    }

    public static function assertIsValidResourceObject($resource)
    {
        PHPUnit::assertIsArray(
            $resource,
            JsonApiAssertMessages::JSONAPI_ERROR_RESOURCE_IS_NOT_ARRAY
        );

        static::assertResourceHasIdMember($resource);
        static::assertResourceHasTypeMember($resource);

        static::assertContainsAtLeastOneMember(['attributes', 'relationships', 'links', 'meta'], $resource);

        $allowed = ['id', 'type', 'meta', 'attributes', 'links', 'relationships'];
        static::assertContainsOnlyAllowedMembers($allowed, $resource);

        if (isset($resource['attributes'])) {
            static::assertIsValidAttributesObject($resource['attributes']);
        }

        if (isset($resource['relationships'])) {
            static::assertIsValidRelationshipsObject($resource['relationships']);
        }

        if (isset($resource['links'])) {
            static::assertIsValidLinksObject($resource['links'], false, false);
        }

        if (isset($resource['meta'])) {
            static::assertIsValidMetaObject($resource['meta']);
        }
    }

    public static function assertIsValidMetaObject($meta)
    {
        static::assertIsNotArrayOfObject(
            $meta,
            JsonApiAssertMessages::JSONAPI_ERROR_META_OBJECT_IS_NOT_ARRAY
        );

        foreach (array_keys($meta) as $key) {
            static::assertIsValidMemberName($key);
        }
    }

    public static function assertIsValidAttributesObject($attributes)
    {
        static::assertIsNotArrayOfObject(
            $attributes,
            JsonApiAssertMessages::JSONAPI_ERROR_ATTRIBUTES_OBJECT_IS_NOT_ARRAY
        );

        foreach ($attributes as $key => $value) {
            static::assertIsValidMemberName($key);
            static::assertIsValidField($value);
        }
    }

    public static function assertIsValidLinksObject($links, $withPagination, $forError)
    {
        PHPUnit::assertIsArray(
            $links,
            JsonApiAssertMessages::JSONAPI_ERROR_LINKS_OBJECT_NOT_ARRAY
        );

        if ($forError) {
            PHPUnit::assertArrayHasKey('about', $links);
            $allowed = ['about'];
        } else {
            $allowed = ['self', 'related'];
            if ($withPagination) {
                $allowed = array_merge($allowed, ['first', 'last', 'next', 'prev']);
            }
        }
        static::assertContainsOnlyAllowedMembers(
            $allowed,
            $links
        );

        foreach ($links as $key => $link) {
            static::assertIsValidLinkObject($link);
        }
    }

    public static function assertIsValidLinkObject($link)
    {
        if (is_null($link)) {
            PHPUnit::assertNull($link);

            return;
        }

        if (!is_array($link)) {
            PHPUnit::assertIsString($link);

            return;
        }

        PHPUnit::assertArrayHasKey(
            'href',
            $link,
            JsonApiAssertMessages::JSONAPI_ERROR_LINK_OBJECT_MISS_HREF_MEMBER
        );

        $allowed = ['href', 'meta'];
        static::assertContainsOnlyAllowedMembers(
            $allowed,
            $link
        );

        if (isset($link['meta'])) {
            static::assertIsValidMetaObject($link['meta']);
        }
    }

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
        static:: assertIsValidLinksObject($links, false, true);
    }

    public static function assertIsValidErrorSourceObject($source)
    {
        PHPUnit::assertIsArray(
            $source,
            JsonApiAssertMessages::JSONAPI_ERROR_ERROR_SOURCE_OBJECT_NOT_ARRAY
        );

        foreach (array_keys($source) as $name) {
            static::assertIsValidMemberName($name);
            static::assertNoForbiddenMemberName($name);
        }

        if (isset($source['pointer'])) {
            PHPUnit::assertIsString(
                $source['pointer'],
                JsonApiAssertMessages::JSONAPI_ERROR_ERROR_SOURCE_POINTER_IS_NOT_STRING
            );
            PHPUnit:: assertStringStartsWith(
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

    public static function assertIsValidJsonapiObject($jsonapi)
    {
        static::assertIsNotArrayOfObject(
            $jsonapi,
            JsonApiAssertMessages::JSONAPI_ERROR_JSONAPI_OBJECT_NOT_ARRAY
        );

        $allowed = ['version', 'meta'];
        static::assertContainsOnlyAllowedMembers(
            $allowed,
            $jsonapi
        );

        if (isset($jsonapi['meta'])) {
            static::assertIsValidMetaObject($jsonapi['meta']);
        }
    }

    public static function assertIsValidRelationshipsObject($relationships)
    {
        static::assertIsNotArrayOfObject($relationships);

        foreach ($relationships as $key => $relationship) {
            static::assertIsValidMemberName($key);
            static::assertIsValidRelationshipObject($relationship);
        }
    }

    public static function assertIsValidRelationshipObject($relationship)
    {
        $expected = ['links', 'data', 'meta'];
        static::assertContainsAtLeastOneMember($expected, $relationship);

        $withPagination = false;
        if (isset($relationship['data'])) {
            $data = $relationship['data'];
            static::assertIsValidResourceLinkage($data);
            $withPagination = static::isToManyResourceLinkage($data);
        }

        if (isset($relationship['links'])) {
            $links = $relationship['links'];
            static::assertIsValidLinksObject($links, $withPagination, false);
        }

        if (isset($relationship['meta'])) {
            static::assertIsValidMetaObject($relationship['meta']);
        }
    }

    public static function assertIsValidResourceLinkage($data)
    {
        if (is_null($data)) {
            PHPUnit::assertNull($data);

            return;
        }

        PHPUnit::assertIsArray(
            $data,
            JsonApiAssertMessages::JSONAPI_ERROR_RESOURCE_LINKAGE_NOT_ARRAY
        );

        if (empty($data)) {
            return;
        }

        if (static::isArrayOfObjects($data)) {
            foreach ($data as $resource) {
                static::assertIsValidResourceIdentifierObject($resource);
            }
        } else {
            static::assertIsValidResourceIdentifierObject($data);
        }
    }

    private static function isToManyResourceLinkage($data)
    {
        if (is_null($data)) {
            return false;
        }

        if (!is_array($data)) {
            return false;
        }

        if (empty($data)) {
            return false;
        }

        return static::isArrayOfObjects($data);
    }
}
