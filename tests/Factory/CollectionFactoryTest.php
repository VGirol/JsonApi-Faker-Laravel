<?php

namespace VGirol\JsonApiFaker\Laravel\Tests\Factory;

use Illuminate\Support\Collection;
use PHPUnit\Framework\Assert as PHPUnit;
use VGirol\JsonApiFaker\Exception\JsonApiFakerException;
use VGirol\JsonApiFaker\Laravel\Factory\CollectionFactory;
use VGirol\JsonApiFaker\Laravel\Factory\ResourceIdentifierFactory;
use VGirol\JsonApiFaker\Laravel\Generator;
use VGirol\JsonApiFaker\Laravel\Messages;
use VGirol\JsonApiFaker\Laravel\Tests\TestCase;

class CollectionFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function setCollectionAsCollectionOfModels()
    {
        $resourceType = 'dummy';
        $count = 3;
        $collection = $this->createCollection($count);

        $factory = new class () extends CollectionFactory
        {
            protected function transform($collection, $resourceType): array
            {
                return $collection->map(
                    function ($item) use ($resourceType) {
                        return new ResourceIdentifierFactory($item, $resourceType);
                    }
                )->toArray();
            }
        };
        $factory->setGenerator(new Generator);
        $obj = $factory->setCollection($collection, $resourceType);

        PHPUnit::assertSame($obj, $factory);
        PHPUnit::assertSame($collection, $factory->getIlluminateCollection());
        PHPUnit::assertIsArray($factory->getCollection());

        $expected = $collection->map(
            function ($item) use ($resourceType) {
                return new ResourceIdentifierFactory($item, $resourceType);
            }
        )->toArray();

        PHPUnit::assertEquals($expected, $factory->getCollection());
    }

    /**
     * @test
     */
    public function setCollectionAsCollectionOfModelsFailedNoResourceType()
    {
        $factory = $this->getMockForAbstractClass(CollectionFactory::class);
        $factory->setGenerator(new Generator());

        PHPUnit::assertNull($factory->getIlluminateCollection());
        PHPUnit::assertNull($factory->getCollection());

        $count = 3;
        $collection = $this->createCollection($count);

        $this->expectException(JsonApiFakerException::class);
        $this->expectExceptionMessage(Messages::ERROR_TYPE_NOT_NULL);

        $factory->setCollection($collection, null);
    }

    /**
     * @test
     */
    public function setCollectionAsArrayOfRiFactory()
    {
        $resourceType = 'dummy';
        $factory = $this->getMockForAbstractClass(CollectionFactory::class);
        $factory->setGenerator(new Generator());

        PHPUnit::assertNull($factory->getIlluminateCollection());
        PHPUnit::assertNull($factory->getCollection());

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
        PHPUnit::assertEquals($collection, $factory->getIlluminateCollection());
        PHPUnit::assertEquals($array, $factory->getCollection());
    }

    /**
     * @test
     */
    public function setCollectionAsArrayFailedNoFactory()
    {
        $factory = $this->getMockForAbstractClass(CollectionFactory::class);
        $factory->setGenerator(new Generator());

        PHPUnit::assertNull($factory->getIlluminateCollection());
        PHPUnit::assertNull($factory->getCollection());

        $count = 3;
        $collection = $this->createCollection($count);

        $this->expectException(JsonApiFakerException::class);
        $this->expectExceptionMessage(Messages::ERROR_NOT_FACTORY_INSTANCE);

        $factory->setCollection($collection->toArray());
    }

    /**
     * @test
     */
    public function setCollectionAsArrayFailedNoModel()
    {
        $factory = $this->getMockForAbstractClass(CollectionFactory::class);
        $factory->setGenerator(new Generator());

        PHPUnit::assertNull($factory->getIlluminateCollection());
        PHPUnit::assertNull($factory->getCollection());

        $count = 3;
        $collection = new Collection();
        for ($i = 1; $i <= $count; $i++) {
            $collection->push(new ResourceIdentifierFactory());
        }

        $this->expectException(JsonApiFakerException::class);
        $this->expectExceptionMessage(Messages::ERROR_MODEL_NOT_SET);

        $factory->setCollection($collection->toArray());
    }

    /**
     * @test
     */
    public function toArrayNoCollection()
    {
        $factory = $this->getMockForAbstractClass(CollectionFactory::class);
        $factory->setGenerator(new Generator());

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
        $factory = $this->getMockForAbstractClass(CollectionFactory::class);
        $factory->setGenerator(new Generator());
        $factory->setCollection($collection, $resourceType);

        $result = $factory->toArray();

        $expected = [];

        PHPUnit::assertSame($expected, $result);
    }
}
