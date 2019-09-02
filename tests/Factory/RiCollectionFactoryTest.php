<?php

namespace VGirol\JsonApiFaker\Laravel\Tests\Factory;

use PHPUnit\Framework\Assert as PHPUnit;
use VGirol\JsonApiFaker\Laravel\Factory\ResourceIdentifierFactory;
use VGirol\JsonApiFaker\Laravel\Factory\RiCollectionFactory;
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

        $factory = new RiCollectionFactory();
        $obj = $factory->setCollection($collection, $resourceType);

        PHPUnit::assertSame($obj, $factory);
        PHPUnit::assertEquals($collection, $factory->collection);
        PHPUnit::assertSame($collection, $factory->collection);
        PHPUnit::assertIsArray($factory->array);

        $expected = $collection->map(
            function ($item) use ($resourceType) {
                return new ResourceIdentifierFactory($item, $resourceType);
            }
        )->toArray();

        PHPUnit::assertEquals($expected, $factory->array);
    }
}
