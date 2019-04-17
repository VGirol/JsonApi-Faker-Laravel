<?php

use Illuminate\Foundation\Testing\TestResponse;
use VGirol\JsonApiAssert\Laravel\Assert;

TestResponse::macro('assertJsonApiNoContent', function () {
    Assert::assertIsNoContentResponse($this);
});
