<?php

namespace VGirol\JsonApiAssert\Laravel\Asserts\Response;

use Illuminate\Foundation\Testing\TestResponse;

trait AssertFetchedCollection
{
    /**
     * Asserts that the response has 200 status code and content with primary data
     * corresponding to the provided collection and resource type.
     *
     * @param TestResponse $response
     * @param Illuminate\Database\Eloquent\Collection $expectedCollection
     * @param string $expectedResourceType
     * @param boolean $strict If true, unsafe characters are not allowed when checking members name.
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public static function assertFetchedResourceCollectionResponse(
        TestResponse $response,
        $expectedCollection,
        $expectedResourceType,
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
        static::assertResourceCollectionEquals($expectedCollection, $expectedResourceType, $data);
    }
}
