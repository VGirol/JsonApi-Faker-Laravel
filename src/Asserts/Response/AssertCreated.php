<?php

namespace VGirol\JsonApiAssert\Laravel\Asserts\Response;

use Illuminate\Foundation\Testing\TestResponse;
use PHPUnit\Framework\Assert as PHPUnit;
use VGirol\JsonApiAssert\Members;

/**
 * Created response
 */
trait AssertCreated
{
    /**
     * Asserts that a response object is a valid '201 Created' response following a creation request.
     *
     * @param \Illuminate\Foundation\Testing\TestResponse $response
     * @param $expected
     * @param boolean $strict If true, unsafe characters are not allowed when checking members name.
     *
     * @throws \PHPUnit\Framework\ExpectationFailedException
     */
    public static function assertIsCreatedResponse(
        TestResponse $response,
        $expected,
        bool $strict
    ) {
        $response->assertStatus(201);
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
        static::assertResourceObjectEquals(
            $expected,
            $data
        );

        // Checks Location header
        $header = $response->headers->get('Location');
        if (!is_null($header) && isset($data[Members::LINKS][Members::SELF])) {
            PHPUnit::assertEquals(
                $header,
                $data[Members::LINKS][Members::SELF]
            );
        }
    }
}
