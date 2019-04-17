<?php

use Illuminate\Foundation\Testing\TestResponse;
use VGirol\JsonApiAssert\Laravel\Assert;

TestResponse::macro('assertJsonApiCreated', function ($expectedModel, $resourceType, $strict = false) {
    Assert::assertIsCreatedResponse($this, $expectedModel, $resourceType, $strict);
});
