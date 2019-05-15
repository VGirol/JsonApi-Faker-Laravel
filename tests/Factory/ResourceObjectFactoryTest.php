<?php
namespace VGirol\JsonApiAssert\Laravel\Tests\Factory;

use PHPUnit\Framework\Assert as PHPUnit;
use VGirol\JsonApiAssert\Laravel\Factory\HelperFactory;
use VGirol\JsonApiAssert\Laravel\Tests\TestCase;

class ResourceObjectFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function resourceObjectFactory()
    {
        $model = $this->createModel();
        $expected = $this->createResource($model, false, null);

        $factory = HelperFactory::create('resource-object', $model, $this->resourceType, $this->routeName);

        $result = $factory->toArray();

        PHPUnit::assertSame($expected, $result);
    }

    /**
     * @test
     */
    public function resourceObjectFactoryWithRelationships()
    {
        $name = 'related';
        $model = $this->createModel();

        $count = 5;
        $related = $this->createCollection($count);

        $model->setRelation($name, $related);

        $expected = $this->createResource($model, false, null, [
            'relationships' => [
                $name => [
                    'data' => $this->createResourceCollection($related, true, null)
                ]
            ]
        ]);

        $factory = HelperFactory::create('resource-object', $model, $this->resourceType, $this->routeName);
        $factory->loadRelationship($name, $this->resourceType);

        $result = $factory->toArray();

        PHPUnit::assertSame($expected, $result);
    }
}
