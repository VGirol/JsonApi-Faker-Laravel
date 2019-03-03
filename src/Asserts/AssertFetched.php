<?php

namespace VGirol\JsonApiAssert\Laravel\Asserts;

use Illuminate\Foundation\Testing\TestResponse;
use VGirol\JsonApiAssert\Assert as JsonApiAssert;

trait AssertFetched
{
    public static function assertFetchedSingleResource(TestResponse $response, $expectedModel, $resourceType)
    {
        $response->assertStatus(200);
        $response->assertHeader(static::$headerName, static::$mediaType);

        // Decode JSON response
        $json = $response->json();

        // Checks response structure
        JsonApiAssert::assertHasValidStructure($json);

        // Checks data member
        JsonApiAssert::assertHasData($json);
        $data = $json['data'];
        
        static::assertResourceObjectEqualsModel($expectedModel, $resourceType, $data);
    }
}
