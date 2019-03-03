<?php

use Illuminate\Foundation\Testing\TestResponse;
use VGirol\JsonApiAssert\Laravel\AssertResponse;

TestResponse::macro('assertJsonApiNoContent', function () {
    AssertResponse::assertJsonApiNoContent($this);
});
