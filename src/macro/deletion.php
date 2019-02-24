<?php

use Illuminate\Foundation\Testing\TestResponse;
use VGirol\JsonApiAssert\Laravel\AssertResponse;

TestResponse::macro('assertJsonApiDeletion', function ($withContent) {
    if ($withContent) {
        AssertResponse::assertDeletion($this);
    } else {
        AssertResponse::assertDeletionWithNoContent($this);
    }
});
