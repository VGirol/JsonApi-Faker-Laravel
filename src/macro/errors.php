<?php

use Illuminate\Foundation\Testing\TestResponse;
use VGirol\JsonApiAssert\Laravel\AssertResponse;

TestResponse::macro('assertJsonApiErrorResponse', function ($statusCode, $expectedErrors) {
    AssertResponse::assertErrorResponse($this, $statusCode, $expectedErrors);
});

TestResponse::macro('assertJsonApiErrors', function ($expectedErrors) {
    AssertResponse::assertErrors($this, $expectedErrors);
});
