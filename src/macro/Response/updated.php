<?php

use Illuminate\Foundation\Testing\TestResponse;
use VGirol\JsonApiAssert\Laravel\Assert;

TestResponse::macro('assertJsonApiUpdated', function ($expected, $strict = false) {
    Assert::assertIsUpdatedResponse($this, $expected, $strict);
});
