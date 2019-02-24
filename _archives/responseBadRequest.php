<?php

use VGirol\JsonApiAssert\JsonApiAssert;
use PHPUnit\Framework\Assert as PHPUnit;
use Illuminate\Foundation\Testing\TestResponse;
use VGirol\JsonApiAssert\Laravel\JsonApiAssertResponse;

TestResponse::macro('assertJsonApiBadRequest', function ($expectedStatus, $expectedMsg) {
    $this->assertStatus($expectedStatus);

    $this->assertJsonApiResponseHeaders();

    // Decode JSON response
    $content = $this->json();

    JsonApiAssert::assertHasValidStructure($content);

    // Check errors member
    JsonApiAssert::assertHasErrors($content);
    $errors = $content['errors'];
    PHPUnit::assertIsArray($errors);
    PHPUnit::assertCount(1, $errors);
    JsonApiAssertResponse::assertResponseErrorObjectEquals($expectedStatus, $expectedMsg, $errors[0]);
});
