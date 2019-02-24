<?php

namespace VGirol\JsonApiAssert\Laravel\Asserts;

use PHPUnit\Framework\Assert as PHPUnit;
use Illuminate\Foundation\Testing\TestResponse;
use VGirol\JsonApiAssert\Assert as JsonApiAssert;
use VGirol\JsonApiAssert\Laravel\Constraint\ContainsOnlyMemberConstraint;

trait AssertDeletion
{
    public static function assertDeletionWithNoContent(TestResponse $response)
    {
        $response->assertStatus(204);
        $response->assertHeader(static::$headerName, static::$mediaType);

        // Decode JSON response
        $content = $response->json();

        PHPUnit::assertEmpty($content);
    }

    public static function assertDeletion(TestResponse $response)
    {
        $response->assertStatus(200);
        $response->assertHeader(static::$headerName, static::$mediaType);

        // Decode JSON response
        $content = $response->json();

        static::assertContainsOnlyMember(['meta'], $content);
        JsonApiAssert::assertIsValidMetaObject($content['meta']);
    }

    public static function assertContainsOnlyMember($expected, $actual, $message = '')
    {
        PHPUnit::assertThat($actual, self::containsOnlyMemberConstraint($expected), $message);
    }

    public static function containsOnlyMemberConstraint($expected)
    {
        return new ContainsOnlyMemberConstraint($expected);
    }

}
