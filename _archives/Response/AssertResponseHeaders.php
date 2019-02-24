<?php

namespace VGirol\JsonApiAssert\Laravel\Response;

use Illuminate\Foundation\Testing\TestResponse;
use PHPUnit\Framework\Assert as PHPUnit;

trait AssertResponseHeaders
{
    public static function assertResponseHeaders(TestResponse $response)
    {
        $response->assertHeader(static::$headerName, static::$mediaType);

        // $headerName = strtolower(static::$headerName);
        // $mediaType = static::$mediaType;

        // $headers = array_change_key_case($response->headers->all(), CASE_LOWER);

        // PHPUnit::assertArrayHasKey(
        //     $headerName,
        //     $headers,
        //     "Header [{$headerName}] not present on response."
        // );

        // $headerValues = $headers[$headerName];
        // PHPUnit::assertCount(1, $headerValues);

        // $actual = $headerValues[0];

        // PHPUnit::assertEquals(
        //     $mediaType,
        //     $actual,
        //     "Header [{$headerName}] was found, but value [{$actual}] does not match [{$mediaType}]."
        // );
    }
}
