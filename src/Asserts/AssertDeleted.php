<?php

namespace VGirol\JsonApiAssert\Laravel\Asserts;

use PHPUnit\Framework\Assert as PHPUnit;
use Illuminate\Foundation\Testing\TestResponse;
use VGirol\JsonApiAssert\Assert as JsonApiAssert;

trait AssertDeleted
{
    public static function assertDeleted(TestResponse $response, $expectedMeta = null)
    {
        $response->assertStatus(200);
        $response->assertHeader(static::$headerName, static::$mediaType);

        // Decode JSON response
        $json = $response->json();

        // Checks response structure
        JsonApiAssert::assertHasValidStructure($json);

        JsonApiAssert::assertContainsOnlyAllowedMembers(['meta', 'jsonapi'], $json);
        PHPUnit::assertArrayHasKey('meta', $json);

        // Checks meta object
        $meta = $json['meta'];
        if (!is_null($expectedMeta)) {
            PHPUnit::assertEquals($expectedMeta, $meta);
        }
    }
}
