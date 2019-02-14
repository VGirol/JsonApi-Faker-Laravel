<?php
namespace VGirol\JsonApi\Tools\Assert;

use PHPUnit\Framework\Assert as PHPUnit;
use VGirol\JsonApi\Tools\Assert\JsonApiAssertMessages;

trait JsonApiAssertStructure
{
    public static function assertHasValidStructure($json)
    {
        static::assertHasValidTopLevelMembers($json);

        $isResourceCollection = false;
        if (isset($json['data'])) {
            static::assertIsValidPrimaryData($json['data']);
            $isResourceCollection = static::isArrayOfObjects($json['data']);
        }

        if (isset($json['errors'])) {
            static::assertIsValidErrorsObject($json['errors']);
        }

        if (isset($json['meta'])) {
            static::assertIsValidMetaObject($json['meta']);
        }

        if (isset($json['jsonapi'])) {
            static::assertIsValidJsonapiObject($json['jsonapi']);
        }

        if (isset($json['links'])) {
            static::assertIsValidLinksObject(
                $json['links'],
                $isResourceCollection,
                false
            );
        }

        if (isset($json['included'])) {
            static::assertIsValidIncludedCollection($json['included'], $json['data']);
        }
    }

    public static function assertHasValidTopLevelMembers($json)
    {
        $expected = ['data', 'errors', 'meta'];
        static::assertContainsAtLeastOneMember(
            $expected,
            $json,
            \sprintf(JsonApiAssertMessages::JSONAPI_ERROR_TOP_LEVEL_MEMBERS, implode('", "', $expected))
        );

        PHPUnit::assertFalse(
            isset($json['data']) && isset($json['errors']),
            JsonApiAssertMessages::JSONAPI_ERROR_TOP_LEVEL_DATA_AND_ERROR
        );

        $allowed = ['data', 'errors', 'meta', 'jsonapi', 'links', 'included'];
        static::assertContainsOnlyAllowedMembers(
            $allowed,
            $json
        );

        PHPUnit::assertFALSE(
            !isset($json['data']) && isset($json['included']),
            JsonApiAssertMessages::JSONAPI_ERROR_TOP_LEVEL_DATA_AND_INCLUDED
        );
    }

    public static function assertIsValidPrimaryData($data)
    {
        if (is_null($data)) {
            PHPUnit::assertNull($data);

            return;
        }

        PHPUnit::assertIsArray(
            $data,
            JsonApiAssertMessages::JSONAPI_ERROR_PRIMARY_DATA_NOT_ARRAY
        );

        if (empty($data)) {
            return;
        }

        if (static::isArrayOfObjects($data)) {
            // Resource collection (Resource Objects or Resource Identifier Objects)
            static::assertIsValidResourceCollection($data, true);
        } else {
            // Single Resource (Resource Object or Resource Identifier Object)
            static::assertIsValidSingleResource($data);
        }
    }

    public static function assertIsValidResourceCollection($list, $checkType)
    {
        static::assertIsArrayOfObjects($list);

        $isResourceObjectCollection = null;
        foreach ($list as $index => $resource) {
            if ($checkType) {
            // Assert that all resources of the collection are of same type.
                if ($index == 0) {
                    $isResourceObjectCollection = static::dataIsResourceObject($resource);
                } else {
                    PHPUnit::assertEquals(
                        $isResourceObjectCollection,
                        static::dataIsResourceObject($resource),
                        JsonApiAssertMessages::JSONAPI_ERROR_PRIMARY_DATA_SAME_TYPE
                    );
                }
            }

            // Check the resource
            static::assertIsValidSingleResource($resource);
        }
    }

    public static function assertIsValidSingleResource($resource)
    {
        if (static::dataIsResourceObject($resource)) {
            static::assertIsValidResourceObject($resource);
        } else {
            static::assertIsValidResourceIdentifierObject($resource);
        }
    }

    public static function assertIsValidIncludedCollection($included, $data)
    {
        static::assertIsArrayOfObjects($included);

        static::assertIsValidResourceCollection($included, false);

        $resIdentifiers = array_merge(
            static::getAllResourceIdentifierObjects($data),
            static::getAllResourceIdentifierObjects($included)
        );

        foreach ($included as $inc) {
            PHPUnit::assertTrue(self::existsInArray($inc, $resIdentifiers));
        }
    }

    private static function dataIsResourceObject($resource)
    {
        $expected = ['attributes', 'relationships', 'links'];

        return static::containsAtLeastOneMember($expected, $resource);
    }

    private static function getAllResourceIdentifierObjects($data)
    {
        $arr = [];
        if (empty($data)) {
            return $arr;
        }
        if (!static::isArrayOfObjects($data)) {
            $arr = [$arr];
        }
        foreach ($data as $obj) {
            if (!isset($obj['relationships'])) {
                continue;
            }
            foreach ($obj['relationships'] as $key => $relationship) {
                if (!isset($relationship['data'])) {
                    continue;
                }
                $arr = array_merge(
                    $arr,
                    static::isArrayOfObjects($relationship['data']) ? $relationship['data'] : [$relationship['data']]
                );
            }
        }

        return $arr;
    }

    private static function existsInArray($needle, $arr)
    {
        foreach ($arr as $resIdentifier) {
            if (($resIdentifier['type'] === $needle['type']) && ($resIdentifier['id'] === $needle['id'])) {
                return true;
            }
        }

        return false;
    }
}
