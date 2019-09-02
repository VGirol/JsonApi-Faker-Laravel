<?php

namespace VGirol\JsonApiFaker\Laravel\Tests\Factory;

use PHPUnit\Framework\Assert as PHPUnit;
use VGirol\JsonApiFaker\Laravel\Factory\CollectionFactory;
use VGirol\JsonApiFaker\Laravel\Factory\ResourceObjectFactory;
use VGirol\JsonApiFaker\Laravel\Factory\RoCollectionFactory;
use VGirol\JsonApiFaker\Laravel\Tests\TestCase;

class RoCollectionFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function setCollectionAsCollectionOfModels()
    {
        $resourceType = 'dummy';
        $count = 3;
        $collection = $this->createCollection($count);

        $factory = new RoCollectionFactory();
        $obj = $factory->setCollection($collection, $resourceType);

        PHPUnit::assertSame($obj, $factory);
        PHPUnit::assertEquals($collection, $factory->collection);
        PHPUnit::assertSame($collection, $factory->collection);
        PHPUnit::assertIsArray($factory->array);

        $expected = $collection->map(
            function ($item) use ($resourceType) {
                return new ResourceObjectFactory($item, $resourceType);
            }
        )->toArray();

        PHPUnit::assertEquals($expected, $factory->array);
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

        $factory = new RoCollectionFactory($collection, $resourceType, $routeName);
        $obj = $factory->appendRelationships([$relName => $relResourceType]);

        PHPUnit::assertSame($obj, $factory);

        $factory->each(function ($resFactory) use ($relName) {
            PHPUnit::assertCount(1, $resFactory->relationships);
            PHPUnit::assertArrayHasKey($relName, $resFactory->relationships);
        });
    }
}
