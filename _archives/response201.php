<?php

use Illuminate\Foundation\Testing\TestResponse;
use VGirol\JsonApi\Models\JsonApiModelInterface;

TestResponse::macro('assertJsonApiResponse201', function (JsonApiModelInterface $model) {
    $attributes = $model->toArray();

    // Check response status code
    $this->assertStatus(201);

    // Decode JSON response
    $json = $this->json();

    // Checks response structure
    JsonApiAssert::assertHasValidStructure($json);

    // Checks data member
    JsonApiAssert::assertHasData($json);
    $data = $json['data'];
    $this->assertResourceObjectEqualsModel($model, $data);
    PHPUnit::assertEquals($attributes, $data['attributes']);

    // Checks Location header
    $header = $this->headers->get('Location');
    if (!is_null($header) && isset($data['links']['self'])) {
        PHPUnit::assertEquals($header, $data['links']['self']);
    }
});
