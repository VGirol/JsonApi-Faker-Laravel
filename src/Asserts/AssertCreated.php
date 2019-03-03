<?php

namespace VGirol\JsonApiAssert\Laravel\Asserts;

use PHPUnit\Framework\Assert as PHPUnit;
use Illuminate\Foundation\Testing\TestResponse;
use VGirol\JsonApiAssert\Assert as JsonApiAssert;

trait AssertCreated
{
    public static function assertCreated(TestResponse $response, $expectedModel, $resourceType)
    {
        $response->assertStatus(201);
        $response->assertHeader(static::$headerName, static::$mediaType);

        // Decode JSON response
        $json = $response->json();

        // Checks response structure
        JsonApiAssert::assertHasValidStructure($json);

        // Checks data member
        JsonApiAssert::assertHasData($json);
        $data = $json['data'];
        static::assertResourceObjectEqualsModel($expectedModel, $resourceType, $data);

        // Checks Location header
        $header = $response->headers->get('Location');
        if (!is_null($header) && isset($data['links']['self'])) {
            PHPUnit::assertEquals($header, $data['links']['self']);
        }
    }
}
