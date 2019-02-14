<?php
namespace VGirol\JsonApi\Tests\Feature\Response;

use VGirol\JsonApi\Tests\TestCase;
use PHPUnit\Framework\Assert as PHPUnit;

class JsonApiCreatingResourceTest extends TestCase
{
    use Common;

    /**
     * POST /endpoint/
     * Should return 201 with data array
     *
     * @return void
     */
    public function testCreatingResource()
    {
        // Creates an object with filled out fields
        $model = factoryJsonapi($this->getModelClassName())->make();
        $model->setIdAttribute(1);
        $attributes = $model->toArray();
        $form = [
            'data' => [
                'type' => $this->getObjectResourceType(),
                'attributes' => array_change_key_case($attributes, CASE_LOWER)
            ]
        ];

        // Sends request and gets response
        $response = $this->json('POST', route("{$this->routeName}.store"), $form);

        // Check response status code
        $response->assertStatus(201);

        // Decode JSON response
        $json = $response->json();

        // Checks response structure
        $this->assertHasValidStructure($json);

        // Checks data member
        $this->assertHasData($json);
        $data = $json['data'];
        $this->assertResourceObjectEqualsModel($model, $data);
        PHPUnit::assertEquals($attributes, $data['attributes']);

        // Checks Location header
        $header = $response->headers->get('Location');
        if (!is_null($header) && isset($data['links']['self'])) {
            PHPUnit::assertEquals($header, $data['links']['self']);
        }
    }

    /**
     * POST /endpoint/
     * Should return 201 with data array
     *
     * @return void
     */
    public function testCreatingResourceWithClientGeneratedId()
    {
        // Creates an object with filled out fields
        $model = factoryJsonapi($this->getModelClassName())->make();
        $model->setIdAttribute(456);
        $attributes = $model->toArray();
        $form = [
            'data' => [
                'type' => $this->getObjectResourceType(),
                'id' => strval($model->getKey()),
                'attributes' => array_change_key_case($attributes, CASE_LOWER)
            ]
        ];

        // Sets config
        config(['jsonapi.return204' => false]);

        // Sends request and gets response
        $response = $this->json('POST', route("{$this->routeName}.store"), $form);

        // Check response status code
        $response->assertStatus(201);

        // Decode JSON response
        $json = $response->json();

        // Checks response structure
        $this->assertHasValidStructure($json);

        // Checks data member
        $this->assertHasData($json);
        $data = $json['data'];
        $this->assertResourceObjectEqualsModel($model, $data);
        PHPUnit::assertEquals($attributes, $data['attributes']);

        // Checks Location header
        $header = $response->headers->get('Location');
        if (!is_null($header) && isset($data['links']['self'])) {
            PHPUnit::assertEquals($header, $data['links']['self']);
        }
    }

    /**
     * POST /endpoint/
     * Should return 204
     *
     * @return void
     */
    public function testCreatingResourceWithClientGeneratedIdReturns204()
    {
        // Creates an object with filled out fields
        $model = factoryJsonapi($this->getModelClassName())->make();
        $model->setIdAttribute(456);
        $attributes = $model->toArray();
        $form = [
            'data' => [
                'type' => $this->getObjectResourceType(),
                'id' => strval($model->getKey()),
                'attributes' => array_change_key_case($attributes, CASE_LOWER)
            ]
        ];

        // Sets config
        config(['jsonapi.return204' => true]);

        // Sends request and gets response
        $response = $this->json('POST', route("{$this->routeName}.store"), $form);

        // Check response status code
        $response->assertStatus(204);
    }

    /**
     * POST /endpoint/
     * Should return 403
     *
     * @return void
     */
    public function testCreatingResourceWithInvalidRequest()
    {
        $form = [
            'invalid' => 'argument'
        ];

        // Sends request and gets response
        $response = $this->json('POST', route("{$this->routeName}.store"), $form);

        // Check response status code
        $response->assertStatus(403);

        // Decode JSON response
        $json = $response->json();

        // Checks response structure
        $this->assertHasValidStructure($json);

        // Check errors member
        $this->assertHasErrors($json);
        $errors = $json['errors'];
        PHPUnit::assertCount(1, $errors);
        $this->assertResponseErrorObjectEquals($errors[0], 403);
    }

    /**
     * POST /endpoint/
     * Should return 409
     *
     * @return void
     */
    public function testCreatingResourceWithInvalidTypeOfResourceObject()
    {
        // Creates an object with filled out fields
        $model = factoryJsonapi($this->getModelClassName())->make();
        $model->setIdAttribute(1);
        $attributes = $model->toArray();
        $form = [
            'data' => [
                'type' => 'badType',
                'attributes' => array_change_key_case($attributes, CASE_LOWER)
            ]
        ];

        // Sends request and gets response
        $response = $this->json('POST', route("{$this->routeName}.store"), $form);

        // Check response status code
        $response->assertStatus(409);

        // Decode JSON response
        $json = $response->json();

        // Checks response structure
        $this->assertHasValidStructure($json);

        // Check errors member
        $this->assertHasErrors($json);
        $errors = $json['errors'];
        PHPUnit::assertCount(1, $errors);
        $this->assertResponseErrorObjectEquals($errors[0], 409);
    }

    /**
     * POST /endpoint/
     * Should return 409
     *
     * @return void
     */
    public function testCreatingResourceWithConflictingClientGeneratedId()
    {
        // Creates an object with filled out fields
        $oldModel = factoryJsonapi($this->getModelClassName())->create();

        $model = factoryJsonapi($this->getModelClassName())->make();
        $model->setIdAttribute($oldModel->getKey());
        $attributes = $model->toArray();
        $form = [
            'data' => [
                'type' => $this->getObjectResourceType(),
                'id' => strval($model->getKey()),
                'attributes' => array_change_key_case($attributes, CASE_LOWER)
            ]
        ];

        // Sends request and gets response
        $response = $this->json('POST', route("{$this->routeName}.store"), $form);

        // Check response status code
        $response->assertStatus(409);

        // Decode JSON response
        $json = $response->json();

        // Checks response structure
        $this->assertHasValidStructure($json);

        // Check errors member
        $this->assertHasErrors($json);
        $errors = $json['errors'];
        PHPUnit::assertCount(1, $errors);
        $this->assertResponseErrorObjectEquals($errors[0], 409);
    }

    /**
     * POST /endpoint/
     * Should return 403
     *
     * @return void
     */
    public function testCreatingResourceWithClientGeneratedIdAndInvalidRequest()
    {
        $form = [
            'data' => [
                'type' => $this->getObjectResourceType(),
                'id' => '1',
                'invalid' => 'request'
            ]
        ];

        // Sends request and gets response
        $response = $this->json('POST', route("{$this->routeName}.store"), $form);

        // Check response status code
        $response->assertStatus(403);

        // Decode JSON response
        $json = $response->json();

        // Checks response structure
        $this->assertHasValidStructure($json);

        // Check errors member
        $this->assertHasErrors($json);
        $errors = $json['errors'];
        PHPUnit::assertCount(1, $errors);
        $this->assertResponseErrorObjectEquals($errors[0], 403);
    }
}
