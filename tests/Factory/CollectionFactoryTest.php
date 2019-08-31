<?php

namespace VGirol\JsonApiFaker\Laravel\Tests\Factory;

use PHPUnit\Framework\Assert as PHPUnit;
use VGirol\JsonApiFaker\Laravel\Factory\CollectionFactory;
use VGirol\JsonApiFaker\Laravel\Tests\TestCase;

class CollectionFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function constructor()
    {
        $resourceType = 'dummy';
        $routeName = 'dummyRoute';
        $collection = null;
        $factory = new CollectionFactory($collection, $resourceType, $routeName);

        PHPUnit::assertNull($factory->collection);
        PHPUnit::assertEquals($resourceType, $factory->resourceType);
        PHPUnit::assertEquals($routeName, $factory->routeName);
    }

    /**
     * @test
     */
    public function isResourceIdentifier()
    {
        $resourceType = null;
        $routeName = null;
        $collection = null;
        $factory = new CollectionFactory($collection, $resourceType, $routeName);

        PHPUnit::assertFalse($factory->isResourceIdentifier());

        $obj = $factory->isResourceIdentifier(true);

        PHPUnit::assertSame($obj, $factory);
        PHPUnit::assertTrue($factory->isResourceIdentifier());
    }

    /**
     * @test
     */
    public function setCollection()
    {
        $resourceType = null;
        $routeName = null;
        $collection = null;
        $factory = new CollectionFactory($collection, $resourceType, $routeName);

        PHPUnit::assertNull($factory->collection);
        PHPUnit::assertNull($factory->array);

        $collection = range(0, 5);
        $expected = collect($collection);

        $obj = $factory->setCollection($collection);

        PHPUnit::assertSame($obj, $factory);
        PHPUnit::assertEquals($collection, $factory->array);
        PHPUnit::assertEquals($expected, $factory->collection);
    }

    /**
     * @test
     */
    public function noCollection()
    {
        $resourceType = null;
        $routeName = null;
        $collection = null;
        $factory = new CollectionFactory($collection, $resourceType, $routeName);
        $result = $factory->toArray();

        PHPUnit::assertNull($result);
    }

    /**
     * @test
     */
    public function emptyCollection()
    {
        $resourceType = 'dummy';
        $routeName = 'dummyRoute';
        $collection = [];
        $factory = new CollectionFactory($collection, $resourceType, $routeName);
        $result = $factory->toArray();

        $expected = [];

        PHPUnit::assertSame($expected, $result);
    }

    /**
     * @test
     * @dataProvider resourceCollectionProvider
     */
    public function resourceCollection($isRI)
    {
        $count = 5;

        $resourceType = 'dummy';
        $routeName = 'dummyRoute';
        $collection = $this->createCollection($count);
        $factory = new CollectionFactory($collection, $resourceType, $routeName, $isRI);

        $result = $factory->toArray();

        $expected = $this->createResourceCollection($collection, $resourceType, $isRI, null);

        PHPUnit::assertSame($expected, $result);
    }

    public function resourceCollectionProvider()
    {
        return [
            'resource identifier' => [
                true
            ],
            'resource object' => [
                false
            ]
        ];
    }

    /**
     * @test
     */
    public function appendRelationships()
    {
        $resourceType = 'dummy';
        $routeName = 'dummyRoute';

        $count = 5;
        $collection = $this->createCollection($count);

        $relName = 'related';
        $relResourceType = 'dummyRelated';
        $collection->each(function ($model) use ($relName) {
            $model->setRelation($relName, $this->createCollection(3));
        });

        $factory = new CollectionFactory($collection, $resourceType, $routeName);
        $obj = $factory->appendRelationships([$relName => $relResourceType]);

        PHPUnit::assertSame($obj, $factory);

        $factory->each(function ($resFactory) use ($relName) {
            PHPUnit::assertCount(1, $resFactory->relationships);
            PHPUnit::assertArrayHasKey($relName, $resFactory->relationships);
        });
    }

    /**
     * @test
     */
    public function appendRelationshipsFailed()
    {
        $resourceType = 'dummy';
        $routeName = 'dummyRoute';

        $collection = $this->createCollection();

        $factory = new CollectionFactory($collection, $resourceType, $routeName, true);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage(CollectionFactory::NO_RELATIONSHIPS_ON_RESOURCE_IDENTIFIER);

        $relName = 'related';
        $relResourceType = 'dummyRelated';
        $factory->appendRelationships([$relName => $relResourceType]);
    }
}
