<?php
namespace VGirol\JsonApi\Tests\Feature\Response;

use PHPUnit\Framework\Assert as PHPUnit;

trait JsonApiDeletingResourceTest
{
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
        $model = factoryJsonapi($this->model)->create();

        $response = $this->json('DELETE', "{$this->endpoint}/{$model->getKey()}");

        $response->assertStatus(204);
    }
}
