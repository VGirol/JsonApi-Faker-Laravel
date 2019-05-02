<?php

use Illuminate\Foundation\Testing\TestResponse;
use VGirol\JsonApiAssert\Laravel\Assert;

TestResponse::macro('assertJsonApiCreated', function ($expected, $strict = false) {
    Assert::assertIsCreatedResponse($this, $expected, $strict);
});
