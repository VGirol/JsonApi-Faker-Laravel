<?php

namespace VGirol\JsonApiAssert\Laravel\Asserts;

use PHPUnit\Framework\Assert as PHPUnit;
use Illuminate\Foundation\Testing\TestResponse;
use VGirol\JsonApiAssert\Assert as JsonApiAssert;

trait AssertNoContent
{
    public static function assertJsonApiNoContent(TestResponse $response)
    {
        $response->assertStatus(204);
        $response->assertHeaderMissing(static::$headerName);

        // Decode JSON response
        $content = $response->getContent();

        PHPUnit::assertEmpty($content);
    }
}
