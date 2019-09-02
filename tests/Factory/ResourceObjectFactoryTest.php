<?php

namespace VGirol\JsonApiFaker\Laravel\Tests\Factory;

use PHPUnit\Framework\Assert as PHPUnit;
use VGirol\JsonApiFaker\Laravel\Factory\ResourceObjectFactory;
use VGirol\JsonApiFaker\Laravel\Testing\DummyModel;
use VGirol\JsonApiFaker\Laravel\Tests\TestCase;

class ResourceObjectFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function constructorWithModel()
    {
        $resourceType = 'dummy';
        $routeName = 'dummyRoute';

        $model = $this->createModel();
        $factory = new ResourceObjectFactory($model, $resourceType, $routeName);

        PHPUnit::assertSame($model, $factory->model);
        PHPUnit::assertEquals($model->getKey(), $factory->id);
        PHPUnit::assertEquals($resourceType, $factory->resourceType);
        PHPUnit::assertEquals($model->attributesToArray(), $factory->attributes);
    }

    /**
     * @test
     */
    public function resourceObjectFactory()
    {
        $resourceType = 'dummy';
        $routeName = 'dummyRoute';

        $model = $this->createModel();
        $expected = $this->createResource($model, $resourceType, false, null);

        $factory = new ResourceObjectFactory($model, $resourceType, $routeName);

        $result = $factory->toArray();

        PHPUnit::assertSame($expected, $result);
    }

    /**
     * @test
     */
    public function loadRelationship()
    {
        $resourceType = 'dummy';
        $routeName = 'dummyRoute';

        $model = $this->createModel();

        $relName = 'related';
        $count = 5;
        $relResourceType = 'dummyRelated';
        $related = $this->createCollection($count);

        $model->setRelation($relName, $related);

        $expected = $this->createResource(
            $model,
            $resourceType,
            false,
            null,
            [
                'relationships' => [
                    $relName => [
                        'data' => $this->createResourceCollection($related, $relResourceType, true, null)
                    ]
                ]
            ]
        );

        $factory = new ResourceObjectFactory($model, $resourceType, $routeName);
        $obj = $factory->loadRelationship($relName, $relResourceType);

        PHPUnit::assertSame($obj, $factory);

        $result = $factory->toArray();

        PHPUnit::assertSame($expected, $result);
    }

    /**
     * @test
     */
    public function loadRelationshipWithEagerLoad()
    {
        $resourceType = 'dummy';
        $routeName = 'dummyRoute';

        $mock = $this->getMockBuilder(DummyModel::class)
            ->setMethods(['load'])
            ->getMock();

        $mock->fakeAttributes();

        $relName = 'related';
        $count = 5;
        $relResourceType = 'dummyRelated';
        $related = $this->createCollection($count);

        $expected = $this->createResource(
            $mock,
            $resourceType,
            false,
            null,
            [
                'relationships' => [
                    $relName => [
                        'data' => $this->createResourceCollection($related, $relResourceType, true, null)
                    ]
                ]
            ]
        );

        $mock->expects($this->once())
            ->method('load')
            ->with($this->equalTo($relName))
            ->willReturnCallback(function ($relName) use ($mock, $related) {
                $mock->setRelation($relName, $related);

                return $mock;
            });

        $factory = new ResourceObjectFactory($mock, $resourceType, $routeName);
        $obj = $factory->loadRelationship($relName, $relResourceType);

        PHPUnit::assertSame($obj, $factory);

        $result = $factory->toArray();

        PHPUnit::assertSame($expected, $result);
    }

    /**
     * @test
     */
    public function appendRelationships()
    {
        $resourceType = 'dummy';
        $routeName = 'dummyRoute';

        $model = $this->createModel();

        $relName = 'related';
        $relResourceType = 'dummyRelated';
        $count = 5;
        $related = $this->createCollection($count);

        $model->setRelation($relName, $related);

        $expected = $this->createResource(
            $model,
            $resourceType,
            false,
            null,
            [
                'relationships' => [
                    $relName => [
                        'data' => $this->createResourceCollection($related, $relResourceType, true, null)
                    ]
                ]
            ]
        );

        $factory = new ResourceObjectFactory($model, $resourceType, $routeName);
        $obj = $factory->appendRelationships([$relName => $relResourceType]);

        PHPUnit::assertSame($obj, $factory);

        $result = $factory->toArray();

        PHPUnit::assertSame($expected, $result);
    }
}
