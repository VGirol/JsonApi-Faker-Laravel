<?php

namespace VGirol\JsonApiAssert\Laravel\Asserts;

use DMS\PHPUnitExtensions\ArraySubset\Assert as AssertArray;
use Illuminate\Foundation\Testing\TestResponse;
use PHPUnit\Framework\Assert as PHPUnit;

trait AssertRelationships
{
    public static function assertResourceObjectContainsRelationship(TestResponse $response, $expectedCollection, $expectedResourceType, $expectedRelationshipName, $resource, $strict)
    {
        static::assertHasRelationships($resource);
        $relationships = $resource['relationships'];

        static::assertHasMember($expectedRelationshipName, $relationships);
        $rel = $relationships[$expectedRelationshipName];
        PHPUnit::assertEquals($expectedCollection->count(), count($rel['data']));

        static::assertResourceLinkageCollectionEquals($expectedCollection, $expectedResourceType, $rel['data'], $strict);
    }
}
