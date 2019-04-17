<?php

use Illuminate\Foundation\Testing\TestResponse;
use VGirol\JsonApiAssert\Laravel\Assert;

TestResponse::macro('assertJsonApiUpdated', function ($expectedModel, $resourceType, $strict = false) {
    Assert::assertIsUpdatedResponse($this, $expectedModel, $resourceType, $strict);
});
