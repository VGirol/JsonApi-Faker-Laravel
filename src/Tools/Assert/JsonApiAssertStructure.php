<?php
namespace VGirol\JsonApi\Tools\Assert;

use PHPUnit\Framework\Assert as PHPUnit;

trait JsonApiAssertStructure
{
    public static $JSONAPI_ERROR_TOP_LEVEL_DATA_AND_ERROR = 'The members "data" and "errors" MUST NOT coexist in the same JSON document.';
    public static $JSONAPI_ERROR_TOP_LEVEL_DATA_AND_INCLUDED = 'If a document does not contain a top-level "data" member, the "included" member MUST NOT be present either.';

    public static $JSONAPI_ERROR_PRIMARY_DATA_NOT_ARRAY = 'Primary data MUST be an array or an arrayable object with a "toArray" method.';
    public static $JSONAPI_ERROR_PRIMARY_DATA_SAME_TYPE = 'All elements of resource collection MUST be of same type (resource object or resource identifier object).';

    public static function checkJsonApiStructure($json)
    {
        static::checkTopLevelMembers($json);

        $isResourceCollection = false;
        if (isset($json['data'])) {
            static::checkPrimaryData($json['data']);
            $isResourceCollection = static::isArrayOfObjects($json['data']);
        }

        if (isset($json['errors'])) {
            static::checkErrorsObject($json['errors']);
        }

        if (isset($json['meta'])) {
            static::checkMetaObject($json['meta']);
        }

        if (isset($json['jsonapi'])) {
            static::checkJsonapiObject($json['jsonapi']);
        }

        if (isset($json['links'])) {
            static::checkLinksObject(
                $json['links'],
                $isResourceCollection,
                false
            );
        }

        if (isset($json['included'])) {
            static::checkIncluded($json['included'], $json['data']);
        }
    }

    public static function checkTopLevelMembers($json)
    {
        $expected = ['data', 'errors', 'meta'];
        static::assertContainsAtLeastOneMember(
            $expected,
            $json,
            \sprintf('A JSON document MUST contain at least one of the following top-level members: "%s".', implode('", "', $expected))
        );

        PHPUnit::assertFalse(
            isset($json['data']) && isset($json['errors']),
            static::$JSONAPI_ERROR_TOP_LEVEL_DATA_AND_ERROR
        );

        $allowed = ['data', 'errors', 'meta', 'jsonapi', 'links', 'included'];
        static::assertContainsOnlyAllowedMembers(
            $allowed,
            $json
        );

        PHPUnit::assertFALSE(
            !isset($json['data']) && isset($json['included']),
            static::$JSONAPI_ERROR_TOP_LEVEL_DATA_AND_INCLUDED
        );
    }

    public static function checkPrimaryData($data)
    {
        if (is_null($data)) {
            PHPUnit::assertNull($data);

            return;
        }

        PHPUnit::assertIsArray(
            $data,
            static::$JSONAPI_ERROR_PRIMARY_DATA_NOT_ARRAY
        );

        if (empty($data)) {
            return;
        }

        if (static::isArrayOfObjects($data)) {
            // Resource collection (Resource Objects or Resource Identifier Objects)
            static::checkDataForResourceCollection($data, true);
        } else {
            // Single Resource (Resource Object or Resource Identifier Object)
            static::checkDataForSingleResource($data);
        }
    }

    public static function checkDataForResourceCollection($list, $checkType)
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
                        static::$JSONAPI_ERROR_PRIMARY_DATA_SAME_TYPE
                    );
                }
            }

            // Check the resource
            static::checkDataForSingleResource($resource);
        }
    }

    public static function checkDataForSingleResource($resource)
    {
        if (static::dataIsResourceObject($resource)) {
            static::checkResourceObject($resource);
        } else {
            static::checkResourceIdentifierObject($resource);
        }
    }

    public static function dataIsResourceObject($resource)
    {
        $expected = ['attributes', 'relationships', 'links'];

        return static::containsAtLeastOneMember($expected, $resource);
    }

    public static function checkIncluded($included, $data)
    {
        static::assertIsArrayOfObjects($included);

        static::checkDataForResourceCollection($included, false);

        $resIdentifiers = array_merge(
            static::getAllResourceIdentifierObjects($data),
            static::getAllResourceIdentifierObjects($included)
        );

        foreach ($included as $inc) {
            PHPUnit::assertTrue(self::existsInArray($inc, $resIdentifiers));
        }
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
