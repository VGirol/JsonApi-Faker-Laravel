<?php
namespace VGirol\JsonApiAssert;

use PHPUnit\Framework\Assert as PHPUnit;
use PHPUnit\Framework\ExpectationFailedException;
use VGirol\JsonApiAssert\JsonApiAssertMessages;

trait JsonApiAssertBase
{
    public static function assertHasMember($json, $key)
    {
        PHPUnit::assertArrayHasKey($key, $json, sprintf(JsonApiAssertMessages::JSONAPI_ERROR_HAS_MEMBER, $key));
    }

    public static function assertNotHasMember($json, $key)
    {
        PHPUnit::assertArrayNotHasKey($key, $json, sprintf(JsonApiAssertMessages::JSONAPI_ERROR_NOT_HAS_MEMBER, $key));
    }

    public static function assertHasData($json)
    {
        static::assertHasMember($json, 'data');
    }

    public static function assertHasAttributes($json)
    {
        static::assertHasMember($json, 'attributes');
    }

    public static function assertHasLinks($json)
    {
        static::assertHasMember($json, 'links');
    }

    public static function assertHasMeta($json)
    {
        static::assertHasMember($json, 'meta');
    }

    public static function assertHasIncluded($json)
    {
        static::assertHasMember($json, 'included');
    }

    public static function assertHasRelationships($json)
    {
        static::assertHasMember($json, 'relationships');
    }

    public static function assertHasErrors($json)
    {
        static::assertHasMember($json, 'errors');
    }

    public static function assertContainsAtLeastOneMember($expected, $actual, $message = '')
    {
        self::assertThat($actual, self::containsAtLeastOneMemberConstraint($expected), $message);
    }

    public static function containsAtLeastOneMemberConstraint($expected)
    {
        return new JsonApiContainsAtLeastOneConstraint($expected);
    }

    public static function containsAtLeastOneMember($expected, $resource)
    {
        $constraint = static::containsAtLeastOneMemberConstraint($expected);

        return $constraint->check($resource);
    }

    public static function assertContainsOnlyAllowedMembers($expected, $actual, $message = '')
    {
        $message = JsonApiAssertMessages::JSONAPI_ERROR_ONLY_ALLOWED_MEMBERS . "\n" . $message;
        self::assertThat($actual, self::containsOnlyAllowedMembersConstraint($expected), $message);
    }

    public static function containsOnlyAllowedMembersConstraint($expected)
    {
        return new JsonApiContainsOnlyAllowedMembersConstraint($expected);
    }

    public static function assertIsArrayOfObjects($data, $message = '')
    {
        PHPUnit::assertIsArray($data, $message);
        PHPUnit::assertTrue(static::isArrayOfObjects($data), $message);
    }

    public static function assertIsNotArrayOfObjects($data, $message = '')
    {
        PHPUnit::assertIsArray($data, $message);
        PHPUnit::assertFalse(static::isArrayOfObjects($data), $message);
    }

    private static function isArrayOfObjects($arr)
    {
        if (!is_array($arr)) {
            return false;
        }
        if (empty($arr)) {
            return true;
        }

        return !static::arrayIsAssociative($arr);
    }

    private static function arrayIsAssociative($arr)
    {
        return (array_keys($arr) !== range(0, count($arr) - 1));
    }

    public static function assertTestFail($fn, $expectedFailureMessage)
    {
        $args = array_slice(func_get_args(), 2);

        try {
            call_user_func_array($fn, $args);
        } catch(ExpectationFailedException $e) {
            if (!is_null($expectedFailureMessage)) {
                PHPUnit::assertContains($expectedFailureMessage, $e->getMessage());
            }

            return;
        }

        throw new ExpectationFailedException(JsonApiAssertMessages::JSONAPI_ERROR_TEST_FAILED);
    }
}
