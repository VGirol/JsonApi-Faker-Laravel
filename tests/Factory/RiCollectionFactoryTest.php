<?php

namespace VGirol\JsonApiFaker\Laravel\Tests\Factory;

use Illuminate\Support\Collection;
use PHPUnit\Framework\Assert as PHPUnit;
use VGirol\JsonApiFaker\Laravel\Factory\ResourceIdentifierFactory;
use VGirol\JsonApiFaker\Laravel\Factory\RiCollectionFactory;
use VGirol\JsonApiFaker\Laravel\Generator;
use VGirol\JsonApiFaker\Laravel\Messages;
use VGirol\JsonApiFaker\Laravel\Tests\TestCase;

class RiCollectionFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function setCollectionAsCollectionOfModels()
    {
        $resourceType = 'dummy';
        $count = 3;
        $collection = $this->createCollection($count);

        $generator = new Generator;
        $factory = new RiCollectionFactory;
        $factory->setGenerator($generator);
        $obj = $factory->setCollection($collection, $resourceType);

        PHPUnit::assertSame($obj, $factory);
        PHPUnit::assertEquals($collection, $factory->collection);
        PHPUnit::assertSame($collection, $factory->collection);
        PHPUnit::assertIsArray($factory->array);

        $expected = $collection->map(
            function ($item) use ($resourceType, $generator) {
                $factory = new ResourceIdentifierFactory($item, $resourceType);
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
                new class
                {
                    // empty
                }
            )
        );

        $generator = new Generator;
        $factory = new RiCollectionFactory;
        $factory->setGenerator($generator);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage(Messages::ERROR_NOT_MODEL_INSTANCE);
        $factory->setCollection($collection, $resourceType);
    }
}
