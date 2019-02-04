<?php
namespace VGirol\JsonApi\Tools\Assert;

use PHPUnit\Framework\Assert as PHPUnit;

trait JsonApiAssertObject
{

    public static $JSONAPI_ERROR_MEMBER_NAME_IS_NOT_STRING = 'Each member key MUST be a string.';

    public static $JSONAPI_ERROR_MEMBER_NAME_NOT_ALLOWED = 'Any object that constitutes or is contained in an attribute MUST NOT contain a "relationships" or "links" member.';

    public static $JSONAPI_ERROR_RESOURCE_ID_MEMBER_IS_ABSENT = 'A resource object MUST contain the "id" top-level members.';
    public static $JSONAPI_ERROR_RESOURCE_ID_MEMBER_IS_EMPTY = 'The value of the "id" member CAN NOT be empty.';
    public static $JSONAPI_ERROR_RESOURCE_ID_MEMBER_IS_NOT_STRING = 'The value of the "id" member MUST be a string.';

    public static $JSONAPI_ERROR_RESOURCE_TYPE_MEMBER_IS_ABSENT = 'A resource object MUST contain the "type" top-level members.';
    public static $JSONAPI_ERROR_RESOURCE_TYPE_MEMBER_IS_EMPTY = 'The value of the "type" member CAN NOT be empty.';
    public static $JSONAPI_ERROR_RESOURCE_TYPE_MEMBER_IS_NOT_STRING = 'The value of the "type" member MUST be a string.';

    public static $JSONAPI_ERROR_RESOURCE_IDENTIFIER_IS_NOT_ARRAY = 'A resource identifier object MUST be an array.';

    public static $JSONAPI_ERROR_RESOURCE_IS_NOT_ARRAY = 'A resource object MUST be an array.';

    public static $JSONAPI_ERROR_META_OBJECT_IS_NOT_ARRAY = 'A meta object MUST be an array.';

    public static $JSONAPI_ERROR_ATTRIBUTES_OBJECT_IS_NOT_ARRAY = 'An attributes object MUST be an array or an arrayable object with a "toArray" method.';

    public static $JSONAPI_ERROR_LINK_OBJECT_MISS_HREF_MEMBER = 'A link object MUST contain an "href" member.';
    public static $JSONAPI_ERROR_LINKS_OBJECT_NOT_ARRAY = 'A links object MUST be an array.';

    public static $JSONAPI_ERROR_ERRORS_OBJECT_NOT_ARRAY = 'Top-level "errors" member MUST be an array of error objects.';
    public static $JSONAPI_ERROR_ERROR_OBJECT_NOT_ARRAY = 'An error object MUST be an array.';

    public static $JSONAPI_ERROR_ERROR_SOURCE_OBJECT_NOT_ARRAY = 'An error source object MUST be an array.';
    public static $JSONAPI_ERROR_ERROR_SOURCE_POINTER_IS_NOT_STRING = 'The value of the "pointer" member MUST be a string.';
    public static $JSONAPI_ERROR_ERROR_SOURCE_POINTER_START = 'The value of the "pointer" member MUST start with a slash (/).';
    public static $JSONAPI_ERROR_ERROR_SOURCE_PARAMETER_IS_NOT_STRING = 'The value of the "parameter" member MUST be a string.';
    public static $JSONAPI_ERROR_ERROR_STATUS_IS_NOT_STRING = 'The value of the "status" member MUST be a string.';
    public static $JSONAPI_ERROR_ERROR_CODE_IS_NOT_STRING = 'The value of the "code" member MUST be a string.';
    public static $JSONAPI_ERROR_ERROR_TITLE_IS_NOT_STRING = 'The value of the "title" member MUST be a string.';
    public static $JSONAPI_ERROR_ERROR_DETAILS_IS_NOT_STRING = 'The value of the "details" member MUST be a string.';

    public static $JSONAPI_ERROR_JSONAPI_OBJECT_NOT_ARRAY = 'A resource linkage MUST be an array of resource objects or resource identifier objects.';

    public static $JSONAPI_ERROR_RESOURCE_LINKAGE_NOT_ARRAY = '';

    public static function checkMemberName($name)
    {
        PHPUnit::assertIsString(
            $name,
            static::$JSONAPI_ERROR_MEMBER_NAME_IS_NOT_STRING
        );

        // TODO : tester caractères
        $forbidden = ['+'];
        PHPUnit::assertNotRegExp('/[\+]/', $name);
    }

    public static function checkField($field)
    {
        if (!is_array($field)) {
            return;
        }

        foreach ($field as $key => $value) {
            // For objects, $key is a string
            // For arrays of objects, $key is an integer
            if (is_string($key)) {
                static::checkForbiddenMemberName($key);
            }
            static::checkField($value);
        }
    }

    public static function checkForbiddenMemberName($name)
    {
        $forbidden = ['relationships', 'links'];
        PHPUnit::assertNotContains(
            $name,
            $forbidden,
            static::$JSONAPI_ERROR_MEMBER_NAME_NOT_ALLOWED
        );
    }

    public static function assertResourceHasIdMember($resource)
    {
        PHPUnit::assertArrayHasKey(
            'id',
            $resource,
            static::$JSONAPI_ERROR_RESOURCE_ID_MEMBER_IS_ABSENT
        );

        PHPUnit::assertNotEmpty(
            $resource['id'],
            static::$JSONAPI_ERROR_RESOURCE_ID_MEMBER_IS_EMPTY
        );

        PHPUnit::assertIsString(
            $resource['id'],
            static::$JSONAPI_ERROR_RESOURCE_ID_MEMBER_IS_NOT_STRING
        );
    }

    public static function assertResourceHasTypeMember($resource)
    {
        PHPUnit::assertArrayHasKey(
            'type',
            $resource,
            static::$JSONAPI_ERROR_RESOURCE_TYPE_MEMBER_IS_ABSENT
        );

        PHPUnit::assertNotEmpty(
            $resource['type'],
            static::$JSONAPI_ERROR_RESOURCE_TYPE_MEMBER_IS_EMPTY
        );

        PHPUnit::assertIsString(
            $resource['type'],
            static::$JSONAPI_ERROR_RESOURCE_TYPE_MEMBER_IS_NOT_STRING
        );

        static::checkMemberName($resource['type']);
    }

    public static function assertIsResourceIdentifierObject($resource)
    {
        PHPUnit::assertIsArray(
            $resource,
            static::$JSONAPI_ERROR_RESOURCE_IDENTIFIER_IS_NOT_ARRAY
        );

        static::assertResourceHasIdMember($resource);
        static::assertResourceHasTypeMember($resource);

        $allowed = ['id', 'type', 'meta'];
        static::assertContainsOnlyAllowedMembers($allowed, $resource);
    }

    public static function assertIsResourceObject($resource)
    {
        PHPUnit::assertIsArray(
            $resource,
            static::$JSONAPI_ERROR_RESOURCE_IS_NOT_ARRAY
        );

        static::assertResourceHasIdMember($resource);
        static::assertResourceHasTypeMember($resource);

        static::assertContainsAtLeastOneMember(['attributes', 'relationships', 'links', 'meta'], $resource);

        $allowed = ['id', 'type', 'meta', 'attributes', 'links', 'relationships'];
        static::assertContainsOnlyAllowedMembers($allowed, $resource);
    }

    public static function checkResourceIdentifierObject($resource)
    {
        static::assertIsResourceIdentifierObject($resource);

        if (isset($resource['meta'])) {
            static::checkMetaObject($resource['meta']);
        }
    }

    public static function checkResourceObject($resource)
    {
        static::assertIsResourceObject($resource);

        if (isset($resource['attributes'])) {
            static::checkAttributes($resource['attributes']);
        }

        if (isset($resource['relationships'])) {
            static::checkRelationshipsObject($resource['relationships']);
        }

        if (isset($resource['links'])) {
            static::checkLinksObject($resource['links'], false, false);
        }

        if (isset($resource['meta'])) {
            static::checkMetaObject($resource['meta']);
        }
    }

    public static function checkMetaObject($meta)
    {
        static::assertIsNotArrayOfObject(
            $meta,
            static::$JSONAPI_ERROR_META_OBJECT_IS_NOT_ARRAY
        );

        foreach (array_keys($meta) as $key) {
            static::checkMemberName($key);
        }
    }

    public static function checkAttributes($attributes)
    {
        static::assertIsNotArrayOfObject(
            $attributes,
            static::$JSONAPI_ERROR_ATTRIBUTES_OBJECT_IS_NOT_ARRAY
        );

        foreach ($attributes as $key => $value) {
            static::checkMemberName($key);
            static::checkField($value);
        }
    }

    public static function checkLinksObject($links, $withPagination, $forError)
    {
        PHPUnit::assertIsArray(
            $links,
            static::$JSONAPI_ERROR_LINKS_OBJECT_NOT_ARRAY
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
            static::checkLinkObject($link);
        }
    }

    public static function checkLinkObject($link)
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
            static::$JSONAPI_ERROR_LINK_OBJECT_MISS_HREF_MEMBER
        );

        $allowed = ['href', 'meta'];
        static::assertContainsOnlyAllowedMembers(
            $allowed,
            $link
        );

        if (isset($link['meta'])) {
            static::checkMetaObject($link['meta']);
        }
    }

    public static function checkErrorsObject($errors)
    {
        static::assertIsArrayOfObjects(
            $errors,
            static::$JSONAPI_ERROR_ERRORS_OBJECT_NOT_ARRAY
        );

        foreach ($errors as $error) {
            static::checkErrorObject($error);
        }
    }

    public static function checkErrorObject($error)
    {
        PHPUnit::assertIsArray(
            $error,
            static::$JSONAPI_ERROR_ERROR_OBJECT_NOT_ARRAY
        );

        $allowed = ['id', 'links', 'status', 'code', 'title', 'details', 'source', 'meta'];
        static::assertContainsOnlyAllowedMembers(
            $allowed,
            $error
        );

        if (isset($error['status'])) {
            PHPUnit::assertIsString(
                $error['status'],
                static::$JSONAPI_ERROR_ERROR_STATUS_IS_NOT_STRING
            );
        }

        if (isset($error['code'])) {
            PHPUnit::assertIsString(
                $error['code'],
                static::$JSONAPI_ERROR_ERROR_CODE_IS_NOT_STRING
            );
        }

        if (isset($error['title'])) {
            PHPUnit::assertIsString(
                $error['title'],
                static::$JSONAPI_ERROR_ERROR_TITLE_IS_NOT_STRING
            );
        }

        if (isset($error['details'])) {
            PHPUnit::assertIsString(
                $error['details'],
                static::$JSONAPI_ERROR_ERROR_DETAILS_IS_NOT_STRING
            );
        }

        if (isset($error['source'])) {
            static::checkErrorSourceObject($error['source']);
        }

        if (isset($error['links'])) {
            static::checkErrorLinksObject($error['links']);
        }

        if (isset($error['meta'])) {
            static::checkMetaObject($error['meta']);
        }
    }

    public static function checkErrorLinksObject($links)
    {
        static:: checkLinksObject($links, false, true);
    }

    public static function checkErrorSourceObject($source)
    {
        PHPUnit::assertIsArray(
            $source,
            static::$JSONAPI_ERROR_ERROR_SOURCE_OBJECT_NOT_ARRAY
        );

        foreach (array_keys($source) as $name) {
            static::checkMemberName($name);
            static::checkForbiddenMemberName($name);
        }

        if (isset($source['pointer'])) {
            PHPUnit::assertIsString(
                $source['pointer'],
                static::$JSONAPI_ERROR_ERROR_SOURCE_POINTER_IS_NOT_STRING
            );
            PHPUnit:: assertStringStartsWith(
                '/',
                $source['pointer'],
                static::$JSONAPI_ERROR_ERROR_SOURCE_POINTER_START
            );
        }

        if (isset($source['parameter'])) {
            PHPUnit::assertIsString(
                $source['parameter'],
                static::$JSONAPI_ERROR_ERROR_SOURCE_PARAMETER_IS_NOT_STRING
            );
        }
    }

    public static function checkJsonapiObject($jsonapi)
    {
        static::assertIsNotArrayOfObject(
            $jsonapi,
            static::$JSONAPI_ERROR_JSONAPI_OBJECT_NOT_ARRAY
        );

        $allowed = ['version', 'meta'];
        static::assertContainsOnlyAllowedMembers(
            $allowed,
            $jsonapi
        );

        if (isset($jsonapi['meta'])) {
            static::checkMetaObject($jsonapi['meta']);
        }
    }

    public static function checkRelationshipsObject($relationships)
    {
        static::assertIsNotArrayOfObject($relationships);

        foreach ($relationships as $key => $relationship) {
            static::checkMemberName($key);
            static::checkRelationshipObject($relationship);
        }
    }

    public static function checkRelationshipObject($relationship)
    {
        $expected = ['links', 'data', 'meta'];
        static::assertContainsAtLeastOneMember($expected, $relationship);

        $withPagination = false;
        if (isset($relationship['data'])) {
            $data = $relationship['data'];
            static::checkResourceLinkage($data);
            $withPagination = static::isToManyResourceLinkage($data);
        }

        if (isset($relationship['links'])) {
            $links = $relationship['links'];
            static::checkLinksObject($links, $withPagination, false);
        }

        if (isset($relationship['meta'])) {
            static::checkMetaObject($relationship['meta']);
        }
    }

    public static function checkResourceLinkage($data)
    {
        if (is_null($data)) {
            PHPUnit::assertNull($data);

            return;
        }

        PHPUnit::assertIsArray(
            $data,
            static::$JSONAPI_ERROR_RESOURCE_LINKAGE_NOT_ARRAY
        );

        if (empty($data)) {
            return;
        }

        if (static::isArrayOfObjects($data)) {
            foreach ($data as $resource) {
                static::checkResourceIdentifierObject($resource);
            }
        } else {
            static::checkResourceIdentifierObject($data);
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
