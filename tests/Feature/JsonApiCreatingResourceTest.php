<?php
namespace VGirol\JsonApi\Tests\Feature\Response;

use PHPUnit\Framework\Assert as PHPUnit;

trait JsonApiCreatingResourceTest
{
    /**
     * POST /endpoint/
     * Should return 201 with data array
     *
     * @return void
     */
    // public function testStore()
    // {
    //     // Creates an object with filled out fields
    //     $model = factory($this->model)->make();
    //     $attributes = $model->toArray();
    //     $form = [
    //         'data' => [
    //             'type' => $this->resourceType,
    //             'attributes' => array_change_key_case($attributes, CASE_LOWER)
    //         ]
    //     ];

    //     $response = $this->json('POST', "api/{$this->endpoint}/", $form);

    //     $response->assertStatus(201);

    //     $json = $response->json();
    //     $this
    //         ->checkJsonResponseStructure($json)
    //         ->assertHasData($json);

    //     $data = $json['data'];
    //     $this
    //         ->assertDataIsResourceObject($data)
    //         ->assertResourceObjectIsValid(
    //             $data,
    //             [
    //                 'resource_type' => $this->resourceType,
    //                 'code' => $this->code,
    //                 'endpoint' => $this->endpoint
    //             ]
    //         )
    //         ->assertResourceObjectAttributesEquals($data['attributes'], $attributes);
    // }

    /**
     * POST /endpoint/
     * Should return 422
     *
     * @return void
     */
    // public function testStore_InvalidRequest()
    // {
    //     $form = [
    //         'invalid' => 'argument'
    //     ];

    //     $response = $this->json('POST', "api/{$this->endpoint}/", $form);

    //     $response->assertStatus(422);
    // }
}
