<?php

use Illuminate\Foundation\Testing\TestResponse;
use VGirol\JsonApiAssert\Laravel\AssertResponse;

TestResponse::macro('assertJsonApiDeleted', function ($expectedMeta = null) {
    AssertResponse::assertDeleted($this, $expectedMeta);
});
