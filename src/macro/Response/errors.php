<?php

use Illuminate\Foundation\Testing\TestResponse;
use VGirol\JsonApiAssert\Laravel\Assert;

TestResponse::macro('assertJsonApiErrorResponse', function ($statusCode, $expectedErrors, $strict = false) {
    Assert::assertIsErrorResponse($this, $statusCode, $expectedErrors, $strict);
});
