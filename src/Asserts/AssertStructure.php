<?php
namespace VGirol\JsonApiAssert\Asserts;

use PHPUnit\Framework\Assert as PHPUnit;
use VGirol\JsonApiAssert\Messages;
use PHPUnit\Framework\ExpectationFailedException;

trait AssertStructure
{
    public static function assertHasValidStructure($json)
    {
        static::assertHasValidTopLevelMembers($json);

        // $isResourceCollection = false;
        if (isset($json['data'])) {
            static::assertIsValidPrimaryData($json['data']);
            // $isResourceCollection = static::isArrayOfObjects($json['data']);
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
            static::assertIsValidTopLevelLinksMember($json['links']);
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
            \sprintf(Messages::TOP_LEVEL_MEMBERS, implode('", "', $expected))
        );

        PHPUnit::assertFalse(
            isset($json['data']) && isset($json['errors']),
            Messages::TOP_LEVEL_DATA_AND_ERROR
        );

        $allowed = ['data', 'errors', 'meta', 'jsonapi', 'links', 'included'];
        static::assertContainsOnlyAllowedMembers(
            $allowed,
            $json
        );

        PHPUnit::assertFALSE(
            !isset($json['data']) && isset($json['included']),
            Messages::TOP_LEVEL_DATA_AND_INCLUDED
        );
    }

    public static function assertIsValidTopLevelLinksMember($links)
    {
        $allowed = ['self', 'related', 'first', 'last', 'next', 'prev'];
        static::assertIsValidLinksObject($links, $allowed);
    }

    public static function assertIsValidPrimaryData($data)
    {
        try {
            PHPUnit::assertIsArray(
                $data,
                Messages::PRIMARY_DATA_NOT_ARRAY
            );
            if (empty($data)) {
                return;
            }
        } catch (ExpectationFailedException $e) {
            PHPUnit::assertNull($data);
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
                        Messages::PRIMARY_DATA_SAME_TYPE
                    );
                }
            }

            // Check the resource
            static::assertIsValidSingleResource($resource);
        }
    }

    public static function assertIsValidSingleResource($resource)
    {
        static::assertIsNotArrayOfObjects($resource);

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

        $present = [];
        foreach ($included as $inc) {
            PHPUnit::assertTrue(self::existsInArray($inc, $resIdentifiers));

            if (!isset($present[$inc['type']])) {
                $present[$inc['type']] = [];
            }
            PHPUnit::assertNotContains(
                $inc['id'],
                $present[$inc['type']],
                Messages::COMPOUND_DOCUMENT_ONLY_ONE_RESOURCE
            );
            array_push($present[$inc['type']], $inc['id']);
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
            $data = [$data];
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
