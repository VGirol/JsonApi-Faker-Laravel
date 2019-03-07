<?php

use Illuminate\Foundation\Testing\TestResponse;
use VGirol\JsonApiAssert\Laravel\AssertResponse;

TestResponse::macro('assertJsonApiFetchedSingleResource', function ($expectedModel, $resourceType) {
    AssertResponse::assertFetchedSingleResource($this, $expectedModel, $resourceType);
});

TestResponse::macro('assertJsonApiFetchedResourceCollection', function ($expectedCollection, $options) {
    AssertResponse::assertFetchedResourceCollection($this, $expectedCollection, $options);
});

TestResponse::macro('assertJsonApiFetchedToOneRelationships', function ($expectedCollection, $options) {
    AssertResponse::assertFetchedToOneRelationships($this, $expectedCollection, $options);
});

TestResponse::macro('assertJsonApiFetchedToManyRelationships', function ($expectedCollection, $options) {
    AssertResponse::assertFetchedToManyRelationships($this, $expectedCollection, $$options);
});

TestResponse::macro('assertJsonApiPaginationLinks', function ($expected, $path = null) {
    AssertResponse::assertPaginationLinks($this, $expected, $path);
});

TestResponse::macro('assertJsonApiNoPaginationLinks', function ($path = null) {
    AssertResponse::assertNoPaginationLinks($this, $path);
});

TestResponse::macro('assertJsonApiRelationshipsLinks', function ($expected, $path = null) {
    AssertResponse::assertRelationshipsLinks($this, $expected, $path);
});
