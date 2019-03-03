<?php

namespace VGirol\JsonApiAssert\Laravel\Asserts;

use PHPUnit\Framework\Assert as PHPUnit;
use Illuminate\Foundation\Testing\TestResponse;
use VGirol\JsonApiAssert\Assert as JsonApiAssert;

trait AssertDeleted
{
    public static function assertDeleted(TestResponse $response)
    {
        $response->assertStatus(200);
        $response->assertHeader(static::$headerName, static::$mediaType);

        // Decode JSON response
        $content = $response->json();

        JsonApiAssert::assertContainsOnlyAllowedMembers(['meta', 'jsonapi'], $content);
        PHPUnit::assertArrayHasKey('meta', $content);
        JsonApiAssert::assertIsValidMetaObject($content['meta']);
    }
}
