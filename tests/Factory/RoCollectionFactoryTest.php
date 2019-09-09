<?php

namespace VGirol\JsonApiFaker\Laravel\Tests\Factory;

use Illuminate\Support\Collection;
use PHPUnit\Framework\Assert as PHPUnit;
use VGirol\JsonApiFaker\Exception\JsonApiFakerException;
use VGirol\JsonApiFaker\Laravel\Factory\ResourceObjectFactory;
use VGirol\JsonApiFaker\Laravel\Factory\RoCollectionFactory;
use VGirol\JsonApiFaker\Laravel\Generator;
use VGirol\JsonApiFaker\Laravel\Messages;
use VGirol\JsonApiFaker\Laravel\Tests\TestCase;
use VGirol\JsonApiFaker\Messages as VGirolMessages;

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

        $generator = new Generator();
        $factory = new RoCollectionFactory();
        $factory->setGenerator($generator);
        $obj = $factory->setCollection($collection, $resourceType);

        PHPUnit::assertSame($obj, $factory);
        PHPUnit::assertEquals($collection, $factory->collection);
        PHPUnit::assertSame($collection, $factory->collection);
        PHPUnit::assertIsArray($factory->array);

        $expected = $collection->map(
            function ($item) use ($resourceType, $generator) {
                $factory = new ResourceObjectFactory($item, $resourceType);
                $factory->setGenerator($generator);

                return $factory;
            }
        )->toArray();

        PHPUnit::assertEquals($expected, $factory->array);
    }

    /**
     * @test
     */
    public function setCollectionFailedNoModels()
    {
        $resourceType = 'dummy';
        $collection = new Collection(
            array_fill(
                0,
                5,
                new class() {
                    // empty
                }
            )
        );

        $generator = new Generator();
        $factory = new RoCollectionFactory();
        $factory->setGenerator($generator);

        $this->expectException(JsonApiFakerException::class);
        $this->expectExceptionMessage(Messages::ERROR_NOT_MODEL_INSTANCE);
        $factory->setCollection($collection, $resourceType);
    }

    /**
     * @test
     */
    public function appendRelationships()
    {
        $resourceType = 'dummy';

        $count = 5;
        $collection = $this->createCollection($count);

        $relName = 'related';
        $relResourceType = 'dummyRelated';
        $collection->each(function ($model) use ($relName) {
            $model->setRelation($relName, $this->createCollection(3));
        });

        $factory = new RoCollectionFactory();
        $factory->setGenerator(new Generator());
        $factory->setCollection($collection, $resourceType);
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
        $relName = 'related';
        $relResourceType = 'dummyRelated';

        $factory = new RoCollectionFactory();
        $factory->setGenerator(new Generator());

        $this->expectException(JsonApiFakerException::class);
        $this->expectExceptionMessage(VGirolMessages::ERROR_COLLECTION_NOT_SET);

        $factory->appendRelationships([$relName => $relResourceType]);
    }
}
