<?php

use Illuminate\Foundation\Testing\TestResponse;
use VGirol\JsonApiAssert\Laravel\Assert;

TestResponse::macro(
    'assertJsonApiFetchedSingleResource',
    function ($expectedModel, $resourceType, $strict = false) {
        Assert::assertFetchedSingleResourceResponse($this, $expectedModel, $resourceType, $strict);
    }
);

TestResponse::macro(
    'assertJsonApiFetchedResourceCollection',
    function ($expectedCollection, $expectedResourceType, $strict = false) {
        Assert::assertFetchedResourceCollectionResponse($this, $expectedCollection, $expectedResourceType, $strict);
    }
);

TestResponse::macro(
    'assertJsonApiFetchedRelationships',
    function ($expected, $resourceType = null, $strict = false) {
        Assert::assertFetchedRelationshipsResponse($this, $expected, $resourceType, $strict);
    }
);

// TestResponse::macro('assertJsonApiPaginationLinks', function ($expected) {
//     Assert::assertPaginationLinks($this, $expected);
// });

// TestResponse::macro('assertJsonApiNoPaginationLinks', function () {
//     Assert::assertNoPaginationLinks($this);
// });

// TestResponse::macro('assertJsonApiRelationshipsLinks', function ($expected, $path = null) {
//     Assert::assertRelationshipsLinks($this, $expected, $path);
// });

// TestResponse::macro(
//    'assertResourceObjectContainsRelationship',
// function ($expectedCollection, $expectedResourceType, $expectedRelationshipName, $resource, $strict = false) {
//     Assert::assertResourceObjectContainsRelationship(
//     $this,
//     $expectedCollection,
//     $expectedResourceType,
//     $expectedRelationshipName,
//     $resource,
//     $strict
// );
// }
// );

// TestResponse::macro('assertIncludedObjectContains', function ($expectedCollection, $expectedResourceType) {
//     Assert::assertIncludedObjectContains($this, $expectedCollection, $expectedResourceType);
// });
