<?php

use Illuminate\Foundation\Testing\TestResponse;
use VGirol\JsonApiAssert\Laravel\Assert;

TestResponse::macro(
    'assertJsonApiResponse400',
    function ($expectedErrors, $strict = false) {
        Assert::assertIsErrorResponse($this, 400, $expectedErrors, $strict);
    }
);

TestResponse::macro(
    'assertJsonApiResponse403',
    function ($expectedErrors, $strict = false) {
        Assert::assertIsErrorResponse($this, 403, $expectedErrors, $strict);
    }
);

TestResponse::macro(
    'assertJsonApiResponse404',
    function ($expectedErrors, $strict = false) {
        Assert::assertIsErrorResponse($this, 404, $expectedErrors, $strict);
    }
);

TestResponse::macro(
    'assertJsonApiResponse406',
    function ($expectedErrors, $strict = false) {
        Assert::assertIsErrorResponse($this, 406, $expectedErrors, $strict);
    }
);

TestResponse::macro(
    'assertJsonApiResponse409',
    function ($expectedErrors, $strict = false) {
        Assert::assertIsErrorResponse($this, 409, $expectedErrors, $strict);
    }
);

TestResponse::macro(
    'assertJsonApiResponse415',
    function ($expectedErrors, $strict = false) {
        Assert::assertIsErrorResponse($this, 415, $expectedErrors, $strict);
    }
);
