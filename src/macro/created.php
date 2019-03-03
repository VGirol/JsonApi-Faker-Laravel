<?php

use Illuminate\Foundation\Testing\TestResponse;
use VGirol\JsonApiAssert\Laravel\AssertResponse;

TestResponse::macro('assertJsonApiCreated', function ($expectedModel, $resourceType) {
    AssertResponse::assertCreated($this, $expectedModel, $resourceType);
});
