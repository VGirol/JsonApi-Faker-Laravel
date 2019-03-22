<?php

namespace VGirol\JsonApiAssert\Laravel\Asserts;

use Illuminate\Foundation\Testing\TestResponse;
use VGirol\JsonApiAssert\Assert as JsonApiAssert;
use DMS\PHPUnitExtensions\ArraySubset\Assert as AssertArray;

trait AssertFetchedRelationships
{
    public static function assertFetchedToOneRelationships(TestResponse $response, $expectedModel, $resourceType)
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
        static::assertSingleResourceLinkageEquals($expectedModel, $resourceType, $data);
    }

    public static function assertFetchedToManyRelationships(TestResponse $response, $expectedCollection, $resourceType)
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
        static::assertResponseResourceLinkageListEqualsCollection($expectedCollection, $resourceType, $data);
    }

    public static function assertRelationshipsLinks(TestResponse $response, $expected, $path = null)
    {
        // Decode JSON response
        $json = $response->json();

        if (!is_null($path)) {
            $json = static::getJsonFromPath($json, $path);
        }

        JsonApiAssert::assertHasLinks($json);
        $links = $json['links'];
        JsonApiAssert::assertContainsOnlyAllowedMembers(['self', 'related'], $links);
        AssertArray::assertArraySubset($expected, $links);
    }
}
