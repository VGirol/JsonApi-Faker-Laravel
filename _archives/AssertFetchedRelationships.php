<?php

namespace VGirol\JsonApiAssert\Laravel\Asserts;

use DMS\PHPUnitExtensions\ArraySubset\Assert as AssertArray;
use Illuminate\Foundation\Testing\TestResponse;

trait AssertFetchedRelationships
{
    public static function assertRelationshipsLinks(TestResponse $response, $expected, $path = null)
    {
        // Decode JSON response
        $json = $response->json();

        if (!is_null($path)) {
            $json = static::getJsonFromPath($json, $path);
        }

        static::assertHasLinks($json);
        $links = $json['links'];
        static::assertContainsOnlyAllowedMembers(['self', 'related'], $links);
        AssertArray::assertArraySubset($expected, $links);
    }
}
