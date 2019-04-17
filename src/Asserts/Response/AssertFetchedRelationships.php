<?php

namespace VGirol\JsonApiAssert\Laravel\Asserts\Response;

use Illuminate\Foundation\Testing\TestResponse;

trait AssertFetchedRelationships
{
    /**
     * Asserts that the response has 200 status code and content with primary data
     * represented as resource identifier objects and corresponding to the provided collection
     * or model and resource type.
     *
     * @param TestResponse $response
     * @param Collection|Model|null $expected
     * @param string $resourceType
     * @param boolean $strict
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public static function assertFetchedRelationshipsResponse(
        TestResponse $response,
        $expected,
        $resourceType,
        $strict
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
        static::assertResourceLinkageEquals($expected, $resourceType, $data, $strict);
    }
}
