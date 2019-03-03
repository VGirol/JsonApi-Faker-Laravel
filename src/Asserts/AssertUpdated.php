<?php

namespace VGirol\JsonApiAssert\Laravel\Asserts;

use Illuminate\Foundation\Testing\TestResponse;
use VGirol\JsonApiAssert\Assert as JsonApiAssert;

trait AssertUpdated
{
    public static function assertUpdated(TestResponse $response, $expectedModel = null, $resourceType = null)
    {
        $response->assertStatus(200);
        $response->assertHeader(static::$headerName, static::$mediaType);

        // Decode JSON response
        $json = $response->json();

        // Checks response structure
        JsonApiAssert::assertHasValidStructure($json);

        // Checks presence of "meta" or "data" member
        JsonApiAssert::assertContainsAtLeastOneMember(['meta', 'data'], $json);

        // Checks data member
        if (isset($json['data'])) {
            $data = $json['data'];
            static::assertResourceObjectEqualsModel($expectedModel, $resourceType, $data);
        } else {
            JsonApiAssert::assertHasMeta($json);
        }
    }
}
