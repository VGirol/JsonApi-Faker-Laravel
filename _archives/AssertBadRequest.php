<?php

namespace VGirol\JsonApiAssert\Laravel\Response;

use Illuminate\Foundation\Testing\TestResponse;
use PHPUnit\Framework\Assert as PHPUnit;
use VGirol\JsonApiAssert\Assert;

trait AssertBadRequest
{
    public static function assertResponse406(TestResponse $response)
    {
        static::assertBadRequest(406, $response);
    }

    public static function assertResponse415(TestResponse $response)
    {
        static::assertBadRequest(415, $response);
    }

    private static function assertBadRequest($expectedStatus, TestResponse $response)
    {
        $response->assertStatus($expectedStatus);

        $response->assertHeader(static::$headerName, static::$mediaType);

        // Decode JSON response
        $content = $this->json();

        Assert::assertHasValidStructure($content);

        // Check errors member
        Assert::assertHasErrors($content);
        $errors = $content['errors'];
        PHPUnit::assertIsArray($errors);
        PHPUnit::assertCount(1, $errors);
    }

    private static function assertStatus($expected, $status)
    {
        PHPUnit::assertTrue(
            $expected === $status,
            "Expected status code {$expected} but received {$status}."
        );
    }

    private static function isJson($string) {
        json_decode($string);
        return (json_last_error() === JSON_NONE);
    }

    private static function getContent($response)
    {
        $content = $response['content'];
        if (is_string($content) && static::isJson($content)) {
            $content = json_decode($content, true);
        }

        return $content;
    }
}
