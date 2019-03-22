<?php

use Illuminate\Foundation\Testing\TestResponse;
use VGirol\JsonApiAssert\Laravel\AssertResponse;

TestResponse::macro('assertJsonApiFetchedSingleResource', function ($expectedModel, $resourceType) {
    AssertResponse::assertFetchedSingleResource($this, $expectedModel, $resourceType);
});

TestResponse::macro('assertJsonApiFetchedResourceCollection', function ($expectedCollection, $expectedResourceType) {
    AssertResponse::assertFetchedResourceCollection($this, $expectedCollection, $expectedResourceType);
});

TestResponse::macro('assertJsonApiFetchedToOneRelationships', function ($expectedModel = null, $resourceType = null) {
    AssertResponse::assertFetchedToOneRelationships($this, $expectedModel, $resourceType);
});

TestResponse::macro('assertJsonApiFetchedToManyRelationships', function ($expectedCollection = null, $resourceType = null) {
    AssertResponse::assertFetchedToManyRelationships($this, $expectedCollection, $resourceType);
});

TestResponse::macro('assertJsonApiPaginationLinks', function ($expected) {
    AssertResponse::assertPaginationLinks($this, $expected);
});

TestResponse::macro('assertJsonApiNoPaginationLinks', function () {
    AssertResponse::assertNoPaginationLinks($this);
});

TestResponse::macro('assertJsonApiRelationshipsLinks', function ($expected, $path = null) {
    AssertResponse::assertRelationshipsLinks($this, $expected, $path);
});

TestResponse::macro('assertResourceObjectContainsRelationship', function ($expectedCollection, $expectedResourceType, $expectedRelationshipName, $resource) {
    AssertResponse::assertResourceObjectContainsRelationship($this, $expectedCollection, $expectedResourceType, $expectedRelationshipName, $resource);
});

TestResponse::macro('assertIncludedObjectContains', function ($expectedCollection, $expectedResourceType) {
    AssertResponse::assertIncludedObjectContains($this, $expectedCollection, $expectedResourceType);
});
