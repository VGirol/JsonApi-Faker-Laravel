<?php

namespace VGirol\JsonApiAssert\Laravel\Asserts\Content;

use Illuminate\Foundation\Testing\TestResponse;
use VGirol\JsonApiAssert\Members;

/**
 * Fetched response
 */
trait AssertIncluded
{
    /**
     * Asserts that an include object contains an expected array.
     *
     * @param \Illuminate\Foundation\Testing\TestResponse $response
     * @param array $expected
     */
    public static function assertResponseIncludeContains(TestResponse $response, $expected)
    {
        // Decode JSON response
        $json = $response->json();

        static::assertIncludeContains($expected, $json);
    }

    public static function assertIncludeContains($expected, $json)
    {
        if (!\is_array($expected)) {
            static::invalidArgument(
                2,
                'array',
                $expected
            );
        }

        static::assertHasMember(Members::INCLUDED, $json);

        $included = $json[Members::INCLUDED];

        static::assertIncludeObjectContains($expected, $included);
    }
}
