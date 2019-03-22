<?php

namespace VGirol\JsonApiAssert\Laravel\Asserts;

use Illuminate\Foundation\Testing\TestResponse;
use VGirol\JsonApiAssert\Assert as JsonApiAssert;
use PHPUnit\Framework\Assert as PHPUnit;
use DMS\PHPUnitExtensions\ArraySubset\Assert as AssertArray;

trait AssertFetchedCollection
{
    public static function assertFetchedResourceCollection(TestResponse $response, $expectedCollection, $expectedResourceType)
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
        static::assertResourceObjectListEqualsCollection($expectedCollection, $expectedResourceType, $data);
    }

    public static function assertPaginationLinks(TestResponse $response, $expected)
    {
        // Decode JSON response
        $json = $response->json();

        JsonApiAssert::assertHasLinks($json);
        $links = $json['links'];
        JsonApiAssert::assertContainsAtLeastOneMember(['first', 'last', 'prev', 'next'], $links);
        AssertArray::assertArraySubset($expected, $links);
    }

    public static function assertNoPaginationLinks(TestResponse $response)
    {
        // Decode JSON response
        $json = $response->json();

        if (!isset($json['links'])) {
            PHPUnit::assertTrue(true);

            return;
        }

        $links = $json['links'];
        foreach (['first', 'last', 'prev', 'next'] as $key) {
            PHPUnit::assertArrayNotHasKey($key, $links);
        }
    }

    public static function assertResourceObjectContainsRelationship(TestResponse $response, $expectedCollection, $expectedResourceType, $expectedRelationshipName, $resource)
    {
        JsonApiAssert::assertHasRelationships($resource);
        $relationships = $resource['relationships'];

        JsonApiAssert::assertHasMember($relationships, $expectedRelationshipName);
        $rel = $relationships[$expectedRelationshipName];
        PHPUnit::assertEquals($expectedCollection->count(), count($rel['data']));

        static::assertResponseResourceLinkageListEqualsCollection($expectedCollection, $expectedResourceType, $rel['data']);
    }

    public static function assertIncludedObjectContains(TestResponse $response, $expectedCollection, $expectedResourceType)
    {
        // Decode JSON response
        $json = $response->json();

        JsonApiAssert::assertHasIncluded($json);
        $included = $json['included'];

        foreach($expectedCollection as $expectedModel) {
            $resCollection = collect($included)->filter(function($item) use ($expectedModel, $expectedResourceType) {
                return ($item['id'] == $expectedModel->getKey()) && ($item['type'] == $expectedResourceType);
            });

            PHPUnit::assertEquals(1, $resCollection->count());
            static::assertResourceObjectEqualsModel($expectedModel, $expectedResourceType, $resCollection->first());
        }
    }
}
