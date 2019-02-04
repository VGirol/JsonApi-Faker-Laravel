<?php
namespace VGirol\JsonApi\Tests\Feature\Response;

use PHPUnit\Framework\Assert as PHPUnit;

trait JsonApiFetchingSingleResourceTest
{
    /**
     * GET /endpoint/<id>
     * Should return 200 with data
     *
     * @return void
     */
    public function testFetchSingleResource()
    {
        // Creates an object with filled out fields
        $model = factoryJsonapi($this->model)->create();

        // Sends request and gets response
        $response = $this->json('GET', "{$this->endpoint}/{$model->getKey()}");

        // Check response status code
        $response->assertStatus(200);

        // Decode JSON response
        $json = $response->json();

        // Checks response structure
        $this->checkJsonApiStructure($json);

        // Checks data member
        $this->assertHasData($json);
        $data = $json['data'];
        $this->assertValidResourceObject($data, $model);
    }

    /**
     * GET /endpoint/<id>
     * Should return 404
     *
     * @return void
     */
    public function testFetchSingleResourceThatDoesNotExist()
    {
        // Sends request and gets response
        $response = $this->json('GET', "{$this->endpoint}/666");

        // Check response status code
        $response->assertStatus(404);

        // Decode JSON response
        $json = $response->json();

        // Checks response structure
        $this->checkJsonApiStructure($json);

        // Check errors member
        $this->assertHasErrors($json);
        $errors = $json['errors'];
        PHPUnit::assertCount(1, $errors);
        $this->assertValidErrorObject($errors[0], 404);
    }
}
