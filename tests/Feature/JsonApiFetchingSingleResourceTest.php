<?php
namespace VGirol\JsonApi\Tests\Feature\Response;

use VGirol\JsonApi\Tests\TestCase;
use PHPUnit\Framework\Assert as PHPUnit;

class JsonApiFetchingSingleResourceTest extends TestCase
{
    use Common;

    /**
     * GET /endpoint/<id>
     * Should return 200 with data
     *
     * @return void
     */
    public function testFetchSingleResource()
    {
        // Creates an object with filled out fields
        $model = factoryJsonapi($this->getModelClassName())->create();

        // Sends request and gets response
        $url = route("{$this->routeName}.show", ['id' => $model->getKey()]);
        $response = $this->json('GET', $url);

        // Check response status code
        $response->assertStatus(200);

        // Decode JSON response
        $json = $response->json();

        // Checks response structure
        $this->assertHasValidStructure($json);

        // Checks data member
        $this->assertHasData($json);
        $data = $json['data'];
        $this->assertResourceObjectEqualsModel($model, $data);
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
        $url = route("{$this->routeName}.show", ['id' => 666]);
        $response = $this->json('GET', $url);

        // Check response status code
        $response->assertStatus(404);

        // Decode JSON response
        $json = $response->json();

        // Checks response structure
        $this->assertHasValidStructure($json);

        // Check errors member
        $this->assertHasErrors($json);
        $errors = $json['errors'];
        PHPUnit::assertCount(1, $errors);
        $this->assertResponseErrorObjectEquals($errors[0], 404);
    }
}
