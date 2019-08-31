<?php

namespace VGirol\JsonApiFaker\Laravel\Tests\Factory;

use PHPUnit\Framework\Assert as PHPUnit;
use VGirol\JsonApiFaker\Laravel\Factory\ResourceIdentifierFactory;
use VGirol\JsonApiFaker\Laravel\Tests\TestCase;

class ResourceIdentifierFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function constructor()
    {
        $resourceType = 'dummy';
        $factory = new ResourceIdentifierFactory(null, $resourceType);

        PHPUnit::assertNull($factory->model);
        PHPUnit::assertNull($factory->id);
        PHPUnit::assertEquals($resourceType, $factory->resourceType);
    }

    /**
     * @test
     */
    public function constructorWithModel()
    {
        $resourceType = 'dummy';
        $model = $this->createModel();
        $factory = new ResourceIdentifierFactory($model, $resourceType);

        PHPUnit::assertSame($model, $factory->model);
        PHPUnit::assertEquals($model->getKey(), $factory->id);
        PHPUnit::assertEquals($resourceType, $factory->resourceType);
    }

    /**
     * @test
     */
    public function toArray()
    {
        $resourceType = 'dummy';
        $key = 'key';
        $value = 'value';

        $model = $this->createModel();
        $expected = $this->createResource(
            $model,
            $resourceType,
            true,
            null,
            [
                'meta' => [
                    $key => $value
                ]
            ]
        );

        $factory = new ResourceIdentifierFactory($model, $resourceType);
        $factory->addToMeta($key, $value);

        $result = $factory->toArray();

        PHPUnit::assertEquals($expected, $result);
    }
}
