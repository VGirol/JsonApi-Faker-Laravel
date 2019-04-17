<?php

use Illuminate\Foundation\Testing\TestResponse;
use VGirol\JsonApiAssert\Laravel\Assert;

TestResponse::macro('assertJsonApiDeleted', function ($expectedMeta = null, $strict = false) {
    Assert::assertIsDeletedResponse($this, $expectedMeta, $strict);
});
