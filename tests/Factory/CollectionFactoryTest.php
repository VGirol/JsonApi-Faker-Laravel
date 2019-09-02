<?php

namespace VGirol\JsonApiFaker\Laravel\Tests\Factory;

use PHPUnit\Framework\Assert as PHPUnit;
use VGirol\JsonApiFaker\Laravel\Factory\CollectionFactory;
use VGirol\JsonApiFaker\Laravel\Factory\ResourceIdentifierFactory;
use VGirol\JsonApiFaker\Laravel\Messages;
use VGirol\JsonApiFaker\Laravel\Tests\TestCase;

class CollectionFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function constructorWithNullValues()
    {
        $resourceType = null;
        $collection = null;
        $factory = $this->getMockForAbstractClass(CollectionFactory::class, [$collection, $resourceType]);

        PHPUnit::assertNull($factory->collection);
        PHPUnit::assertNull($factory->array);
    }

    /**
     * @test
     */
    public function setCollectionAsCollectionOfModels()
    {
        $resourceType = 'dummy';
        $count = 3;
        $collection = $this->createCollection($count);

        $factory = new class extends CollectionFactory
        {
            protected function transform($collection, $resourceType): array
            {
                return $collection->map(
                    function ($model) use ($resourceType) {
                        return new ResourceIdentifierFactory($model, $resourceType);
                    }
                )->toArray();
            }
        };
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

    /**
     * @test
     */
    public function setCollectionAsArrayOfRiFactory()
    {
        $resourceType = 'dummy';
        $collection = null;
        $factory = $this->getMockForAbstractClass(CollectionFactory::class, [$collection, $resourceType]);

        PHPUnit::assertNull($factory->collection);
        PHPUnit::assertNull($factory->array);

        $count = 3;
        $collection = $this->createCollection($count);

        /** @param array<ResourceIdentifierFactory> $collection */
        $array = $collection->map(
            function ($item) use ($resourceType) {
                return new ResourceIdentifierFactory($item, $resourceType);
            }
        )->toArray();

        $obj = $factory->setCollection($array);

        PHPUnit::assertSame($obj, $factory);
        PHPUnit::assertEquals($collection, $factory->collection);
        PHPUnit::assertEquals($array, $factory->array);
    }

    /**
     * @test
     */
    public function setCollectionAsArrayFailedNoFactory()
    {
        $resourceType = 'dummy';
        $collection = null;
        $factory = $this->getMockForAbstractClass(CollectionFactory::class, [$collection, $resourceType]);

        PHPUnit::assertNull($factory->collection);
        PHPUnit::assertNull($factory->array);

        $count = 3;
        $collection = $this->createCollection($count);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage(Messages::ERROR_NO_FACTORY);

        $factory->setCollection($collection->toArray());
    }

    /**
     * @test
     */
    public function setCollectionAsArrayFailedNoModel()
    {
        $resourceType = 'dummy';
        $collection = null;
        $factory = $this->getMockForAbstractClass(CollectionFactory::class, [$collection, $resourceType]);

        PHPUnit::assertNull($factory->collection);
        PHPUnit::assertNull($factory->array);

        /** @param array<ResourceIdentifierFactory> $array */
        $array = array_fill(0, 3, new ResourceIdentifierFactory);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage(Messages::ERROR_NO_MODEL);

        $factory->setCollection($array);
    }

    /**
     * @test
     */
    public function setCollectionAsArrayFailedNoResourceType()
    {
        $resourceType = null;
        $collection = null;
        $factory = $this->getMockForAbstractClass(CollectionFactory::class, [$collection, $resourceType]);

        PHPUnit::assertNull($factory->collection);
        PHPUnit::assertNull($factory->array);

        $count = 3;
        $collection = $this->createCollection($count);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage(Messages::ERROR_TYPE_NOT_NULL);

        $factory->setCollection($collection, null);
    }

    /**
     * @test
     */
    public function noCollection()
    {
        $resourceType = null;
        $collection = null;
        $factory = $this->getMockForAbstractClass(CollectionFactory::class, [$collection, $resourceType]);
        $result = $factory->toArray();

        PHPUnit::assertNull($result);
    }

    /**
     * @test
     */
    public function toArrayEmptyCollection()
    {
        $resourceType = 'dummy';
        $collection = [];
        $factory = $this->getMockForAbstractClass(CollectionFactory::class, [$collection, $resourceType]);
        $result = $factory->toArray();

        $expected = [];

        PHPUnit::assertSame($expected, $result);
    }
}
