<?php

use Illuminate\Foundation\Testing\TestResponse;
use VGirol\JsonApiAssert\Laravel\Assert;

TestResponse::macro('assertJsonApiErrorsContains', function ($expectedErrors, $strict = false) {
    Assert::assertErrorsContains($this, $expectedErrors, $strict);
});
