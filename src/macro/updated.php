<?php

use Illuminate\Foundation\Testing\TestResponse;
use VGirol\JsonApiAssert\Laravel\AssertResponse;

TestResponse::macro('assertJsonApiUpdated', function ($expectedModel, $resourceType) {
    AssertResponse::assertUpdated($this, $expectedModel, $resourceType);
});
