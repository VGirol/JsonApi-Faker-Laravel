<?php

namespace VGirol\JsonApiAssert\Laravel\Asserts\Response;

use Illuminate\Foundation\Testing\TestResponse;

trait AssertFetched
{
    /**
     * Asserts that the response has 200 status code and content with primary data
     * corresponding to the provided model and resource type.
     *
     * @param TestResponse $response
     * @param Illuminate\Database\Eloquent\Model $expectedModel
     * @param string $resourceType
     * @param array $strict
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public static function assertFetchedSingleResourceResponse(
        TestResponse $response,
        $expectedModel,
        $resourceType,
        bool $strict
    ) {
        $response->assertStatus(200);
        $response->assertHeader(static::$headerName, static::$mediaType);

        // Decode JSON response
        $json = $response->json();

        // Checks response structure
        static::assertHasValidStructure($json, $strict);

        // Checks data member
        static::assertHasData($json);
        $data = $json['data'];

        static::assertResourceObjectEquals($expectedModel, $resourceType, $data);
    }
}
