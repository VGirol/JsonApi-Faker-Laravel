<?php

namespace VGirol\JsonApiAssert\Laravel\Asserts\Response;

use Illuminate\Foundation\Testing\TestResponse;
use VGirol\JsonApiAssert\Members;

/**
 * Fetched relationships response
 */
trait AssertFetchedRelationships
{
    /**
     * Asserts that the response has 200 status code and content with primary data
     * represented as resource identifier objects and corresponding to the provided collection
     * or model and resource type.
     *
     * @param \Illuminate\Foundation\Testing\TestResponse $response
     * @param array|null $expected
     * @param boolean $strict
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public static function assertFetchedRelationshipsResponse(TestResponse $response, $expected, $strict)
    {
        $response->assertStatus(200);
        $response->assertHeader(
            static::$headerName,
            static::$mediaType
        );

        // Decode JSON response
        $json = $response->json();

        // Checks response structure
        static::assertHasValidStructure(
            $json,
            $strict
        );

        // Checks data member
        static::assertHasData($json);
        $data = $json[Members::DATA];
        static::assertResourceLinkageEquals(
            $expected,
            $data,
            $strict
        );
    }
}
