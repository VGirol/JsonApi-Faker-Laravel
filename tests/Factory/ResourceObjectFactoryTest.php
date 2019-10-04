<?php

namespace VGirol\JsonApiFaker\Laravel\Tests\Factory;

use PHPUnit\Framework\Assert as PHPUnit;
use VGirol\JsonApiFaker\Exception\JsonApiFakerException;
use VGirol\JsonApiFaker\Laravel\Factory\ResourceObjectFactory;
use VGirol\JsonApiFaker\Laravel\Generator;
use VGirol\JsonApiFaker\Laravel\Messages;
use VGirol\JsonApiFaker\Laravel\Tests\DummyModel;
use VGirol\JsonApiFaker\Laravel\Tests\TestCase;

class ResourceObjectFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function constructorWithModel()
    {
        $resourceType = 'dummy';

        $model = $this->createModel();
        $factory = new ResourceObjectFactory($model, $resourceType);

        PHPUnit::assertSame($model, $factory->getModel());
        PHPUnit::assertEquals($model->getKey(), $factory->getId());
        PHPUnit::assertEquals($resourceType, $factory->getResourceType());
        PHPUnit::assertEquals($model->attributesToArray(), $factory->getAttributes());
    }

    /**
     * @test
     */
    public function setValues()
    {
        $resourceType = 'dummy';

        $model = $this->createModel();
        $factory = new ResourceObjectFactory();

        PHPUnit::assertNull($factory->getModel());
        PHPUnit::assertNull($factory->getId());
        PHPUnit::assertNull($factory->getResourceType());
        PHPUnit::assertNull($factory->getAttributes());

        $obj = $factory->setValues($model, $resourceType);

        PHPUnit::assertSame($obj, $factory);
        PHPUnit::assertSame($model, $factory->getModel());
        PHPUnit::assertEquals($model->getKey(), $factory->getId());
        PHPUnit::assertEquals($resourceType, $factory->getResourceType());
        PHPUnit::assertEquals($model->attributesToArray(), $factory->getAttributes());
    }

    /**
     * @test
     */
    public function toArray()
    {
        $resourceType = 'dummy';

        $model = $this->createModel();
        $expected = $this->createResource($model, $resourceType, false, null);

        $factory = new ResourceObjectFactory($model, $resourceType);

        $result = $factory->toArray();

        PHPUnit::assertSame($expected, $result);
    }

    /**
     * @test
     */
    public function loadRelationship()
    {
        $resourceType = 'dummy';

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
                        'data' => $this->createResourceCollection($related, $relResourceType, true, null),
                    ],
                ],
            ]
        );

        $factory = new ResourceObjectFactory($model, $resourceType);
        $obj = $factory->setGenerator(new Generator());

        PHPUnit::assertSame($obj, $factory);
        PHPUnit::assertNull($factory->getRelationships());

        $obj = $factory->loadRelationship($relName, $relResourceType);

        PHPUnit::assertSame($obj, $factory);
        PHPUnit::assertNotNull($factory->getRelationships());

        $result = $factory->toArray();

        PHPUnit::assertSame($expected, $result);
    }

    /**
     * @test
     */
    public function loadRelationshipWithEagerLoad()
    {
        $resourceType = 'dummy';

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
                        'data' => $this->createResourceCollection($related, $relResourceType, true, null),
                    ],
                ],
            ]
        );

        $mock->expects($this->once())
            ->method('load')
            ->with($this->equalTo($relName))
            ->willReturnCallback(function ($relName) use ($mock, $related) {
                $mock->setRelation($relName, $related);

                return $mock;
            });

        $factory = new ResourceObjectFactory($mock, $resourceType);
        $obj = $factory->setGenerator(new Generator());

        PHPUnit::assertSame($obj, $factory);

        $obj = $factory->loadRelationship($relName, $relResourceType);

        PHPUnit::assertSame($obj, $factory);

        $result = $factory->toArray();

        PHPUnit::assertSame($expected, $result);
    }

    /**
     * @test
     */
    public function loadRelationshipFailedNoModel()
    {
        $relName = 'related';
        $relResourceType = 'dummyRelated';

        $factory = new ResourceObjectFactory();
        $factory->setGenerator(new Generator());

        $this->expectException(JsonApiFakerException::class);
        $this->expectExceptionMessage(Messages::ERROR_MODEL_NOT_SET);

        $factory->loadRelationship($relName, $relResourceType);
    }

    /**
     * @test
     */
    public function appendRelationships()
    {
        $resourceType = 'dummy';

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
                        'data' => $this->createResourceCollection($related, $relResourceType, true, null),
                    ],
                ],
            ]
        );

        $factory = new ResourceObjectFactory($model, $resourceType);
        $obj = $factory->setGenerator(new Generator());

        PHPUnit::assertSame($obj, $factory);

        $obj = $factory->appendRelationships([$relName => $relResourceType]);

        PHPUnit::assertSame($obj, $factory);

        $result = $factory->toArray();

        PHPUnit::assertSame($expected, $result);
    }
}
