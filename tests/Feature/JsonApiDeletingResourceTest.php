<?php
namespace VGirol\JsonApi\Tests\Feature\Response;

use VGirol\JsonApi\Tests\TestCase;
use PHPUnit\Framework\Assert as PHPUnit;

class JsonApiDeletingResourceTest extends TestCase
{
    use Common;

    /**
     * DELETE /endpoint/<id>
     * Tests the destroy() method that deletes the model
     * Should return 204
     *
     * @return void
     */
    public function testDestroyAndResponseWithNoContent()
    {
        // Creates an object with filled out fields
        $model = factoryJsonapi($this->getModelClassName())->create();

        $response = $this->json('DELETE', route("{$this->routeName}.destroy", ['id' => $model->getKey()]));

        $response->assertStatus(204);
    }

    public function testDestroyNonExistingResource()
    {
        $response = $this->json('DELETE', route("{$this->routeName}.destroy", ['id' => 666]));

        $response->assertStatus(404);
    }
}
