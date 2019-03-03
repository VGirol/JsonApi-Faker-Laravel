<?php

use Illuminate\Foundation\Testing\TestResponse;
use VGirol\JsonApiAssert\Laravel\AssertResponse;

TestResponse::macro('assertJsonApiResponse403', function ($expectedErrors) {
    AssertResponse::assertErrorResponse($this, 403, $expectedErrors);
});

TestResponse::macro('assertJsonApiResponse404', function ($expectedErrors) {
    AssertResponse::assertErrorResponse($this, 404, $expectedErrors);
});

TestResponse::macro('assertJsonApiResponse406', function ($expectedErrors) {
    AssertResponse::assertErrorResponse($this, 406, $expectedErrors);
});

TestResponse::macro('assertJsonApiResponse409', function ($expectedErrors) {
    AssertResponse::assertErrorResponse($this, 409, $expectedErrors);
});

TestResponse::macro('assertJsonApiResponse415', function ($expectedErrors) {
    AssertResponse::assertErrorResponse($this, 415, $expectedErrors);
});
