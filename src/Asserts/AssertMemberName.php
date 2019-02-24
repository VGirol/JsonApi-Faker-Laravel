<?php
namespace VGirol\JsonApiAssert\Asserts;

use PHPUnit\Framework\Assert as PHPUnit;
use VGirol\JsonApiAssert\Messages;

trait AssertMemberName
{
    public static function assertIsValidMemberName($name, $strict = false)
    {
        PHPUnit::assertIsString(
            $name,
            Messages::MEMBER_NAME_IS_NOT_STRING
        );

        PHPUnit::assertGreaterThanOrEqual(
            1,
            strlen($name),
            Messages::MEMBER_NAME_IS_TOO_SHORT
        );

        // Globally allowed characters
        $globally = '\x{0030}-\x{0039}\x{0041}-\x{005A}\x{0061}-\x{007A}';
        $globallyNotSafe = '\x{0080}-\x{FFFF}';
        // Allowed characters
        $allowed = '\x{002D}\x{005F}';
        $allowedNotSafe = '\x{0020}';

        $regex = $strict ? "/[^{$globally}{$allowed}]+/u" : "/[^{$globally}{$globallyNotSafe}{$allowed}{$allowedNotSafe}]+/u";
        PHPUnit::assertNotRegExp(
            $regex,
            $name,
            Messages::MEMBER_NAME_HAVE_RESERVED_CHARACTERS
        );

        $regex = $strict ? "/^[{$globally}]{1}.*[{$globally}]{1}$/u" : "/^[{$globally}{$globallyNotSafe}]{1}.*[{$globally}{$globallyNotSafe}]{1}$/u";
        PHPUnit::assertRegExp(
            $regex,
            $name,
            Messages::MEMBER_NAME_START_AND_END_WITH_ALLOWED_CHARACTERS
        );
    }

    public static function assertFieldHasNoForbiddenMemberName($field)
    {
        if (!is_array($field)) {
            return;
        }

        foreach ($field as $key => $value) {
            // For objects, $key is a string
            // For arrays of objects, $key is an integer
            if (is_string($key)) {
                static::assertIsNotForbiddenMemberName($key);
            }
            static::assertFieldHasNoForbiddenMemberName($value);
        }
    }

    public static function assertIsNotForbiddenMemberName($name)
    {
        $forbidden = ['relationships', 'links'];
        PHPUnit::assertNotContains(
            $name,
            $forbidden,
            Messages::MEMBER_NAME_NOT_ALLOWED
        );
    }
}
