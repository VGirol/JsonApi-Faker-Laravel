<?php

namespace VGirol\JsonApiAssert\Laravel\Asserts\Content;

use Illuminate\Foundation\Testing\TestResponse;
use PHPUnit\Framework\Assert as PHPUnit;
use VGirol\JsonApiAssert\Members;

trait AssertPagination
{
    public static function assertResponseHasNoPaginationLinks(TestResponse $response): void
    {
        // Decode JSON response
        $json = $response->json();

        if (!isset($json[Members::LINKS])) {
            PHPUnit::assertTrue(true);

            return;
        }

        $links = $json[Members::LINKS];
        static::assertHasNoPaginationLinks($links);
    }

    public static function assertResponseHasPaginationLinks(TestResponse $response, $expected)
    {
        // Decode JSON response
        $json = $response->json();

        static::assertHasLinks($json);
        $links = $json[Members::LINKS];
        static::assertPaginationLinksEquals($expected, $links);
    }
}
