<?php

use Illuminate\Foundation\Testing\TestResponse;
use VGirol\JsonApiAssert\Laravel\AssertResponse;

TestResponse::macro('assertJsonApiResponse406', function ($expectedMsg) {
    AssertResponse::assertResponse406($this);
});

TestResponse::macro('assertJsonApiResponse415', function ($expectedMsg) {
    AssertResponse::assertResponse415($this);
});
