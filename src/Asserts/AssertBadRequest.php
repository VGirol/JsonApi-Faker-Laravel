<?php

namespace VGirol\JsonApiAssert\Laravel\Asserts;

use PHPUnit\Framework\Assert as PHPUnit;
use Illuminate\Foundation\Testing\TestResponse;
use VGirol\JsonApiAssert\Assert as JsonApiAssert;

trait AssertBadRequest
{
    public static function assertResponse406(TestResponse $response)
    {
        static::assertBadRequest($response, 406);
    }

    public static function assertResponse415(TestResponse $response)
    {
        static::assertBadRequest($response, 415);
    }

    private static function assertBadRequest(TestResponse $response, $expectedStatus)
    {
        $response->assertStatus($expectedStatus);

        $response->assertHeader(static::$headerName, static::$mediaType);

        // Decode JSON response
        $content = $response->json();

        JsonApiAssert::assertHasValidStructure($content);

        // Check errors member
        JsonApiAssert::assertHasErrors($content);
        $errors = $content['errors'];
        PHPUnit::assertIsArray($errors);
        PHPUnit::assertCount(1, $errors);
    }
}
