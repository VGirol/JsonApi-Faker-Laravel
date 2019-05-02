<?php

use Illuminate\Foundation\Testing\TestResponse;
use VGirol\JsonApiAssert\Laravel\Assert;

TestResponse::macro(
    'assertJsonApiTopLevelLinksObject',
    function ($expected) {
        Assert::assertTopLevelLinksObjectEquals($this, $expected);
    }
);

TestResponse::macro(
    'assertJsonApiTopLevelLinksObjectContains',
    function ($expected) {
        Assert::assertTopLevelLinksObjectContains($this, $expected);
    }
);
