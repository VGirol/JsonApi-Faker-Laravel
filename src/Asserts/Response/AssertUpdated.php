<?php

namespace VGirol\JsonApiAssert\Laravel\Asserts\Response;

use Illuminate\Foundation\Testing\TestResponse;

trait AssertUpdated
{
    /**
     * Asserts that a response object is a valid '200 OK' response following an update request.
     *
     * @param Illuminate\Foundation\Testing\TestResponse $response
     * @param Illuminate\Database\Eloquent\Model $expectedModel
     * @param string $resourceType
     * @param boolean $strict If true, unsafe characters are not allowed when checking members name.
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public static function assertIsUpdatedResponse(
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

        // Checks presence of "meta" or "data" member
        static::assertContainsAtLeastOneMember(['meta', 'data'], $json);

        // Checks data member
        if (isset($json['data'])) {
            $data = $json['data'];
            static::assertResourceObjectEquals($expectedModel, $resourceType, $data);
        }
    }
}
