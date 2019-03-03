<?php

namespace VGirol\JsonApiAssert\Laravel\Asserts;

use Illuminate\Foundation\Testing\TestResponse;
use VGirol\JsonApiAssert\Assert as JsonApiAssert;
use PHPUnit\Framework\Assert as PHPUnit;

trait AssertFetchedCollection
{
    public static function assertFetchedResourceCollection(TestResponse $response, $expectedCollection, $options)
    {
        $response->assertStatus(200);
        $response->assertHeader(static::$headerName, static::$mediaType);

        // Decode JSON response
        $json = $response->json();

        // Checks response structure
        JsonApiAssert::assertHasValidStructure($json);

        // Checks data member
        JsonApiAssert::assertHasData($json);
        $data = $json['data'];
        static::assertResourceObjectListEqualsCollection($expectedCollection, $data, $options);
    }

    public static function assertPaginationLinks(TestResponse $response, $expected, $path = null)
    {
        // Decode JSON response
        $json = $response->json();

        if (!is_null($path)) {
            $json = static::getJsonFromPath($json, $path);
        }

        JsonApiAssert::assertHasLinks($json);
        $links = $json['links'];
        JsonApiAssert::assertContainsOnlyAllowedMembers(['first', 'last', 'prev', 'next'], $links);
        PHPUnit::assertArraySubset($expected, $links);
    }

    public static function getJsonFromPath($json, $path)
    {
        $path = explode('.', $path);
        foreach ($path as $member) {
            JsonApiAssert::assertHasMember($json, $member);
            $json = $json[$member];
        }

        return $json;
    }
}
