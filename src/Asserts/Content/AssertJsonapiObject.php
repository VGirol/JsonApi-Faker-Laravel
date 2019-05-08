<?php

namespace VGirol\JsonApiAssert\Laravel\Asserts\Content;

use Illuminate\Foundation\Testing\TestResponse;
use VGirol\JsonApiAssert\Members;

trait AssertJsonapiObject
{
    /**
     * Asserts that a jsonapi object equals an expected array.
     *
     * @param \Illuminate\Foundation\Testing\TestResponse $response
     * @param array $expected
     */
    public static function assertResponseJsonapiObjectEquals(TestResponse $response, $expected)
    {
        if (!\is_array($expected)) {
            static::invalidArgument(
                2,
                'array',
                $expected
            );
        }

        // Decode JSON response
        $json = $response->json();

        static::assertHasMember(Members::JSONAPI, $json);

        $jsonapi = $json[Members::JSONAPI];

        static::assertJsonapiObjectEquals($expected, $jsonapi);
    }
}
