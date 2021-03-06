<?php

namespace VGirol\JsonApiFaker\Laravel\Tests\Factory;

use PHPUnit\Framework\Assert as PHPUnit;
use VGirol\JsonApiFaker\Laravel\Factory\CollectionFactory;
use VGirol\JsonApiFaker\Laravel\Factory\RelationshipFactory;
use VGirol\JsonApiFaker\Laravel\Factory\ResourceIdentifierFactory;
use VGirol\JsonApiFaker\Laravel\Generator;
use VGirol\JsonApiFaker\Laravel\Tests\TestCase;

class RelationshipFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function setDataWithSingleResourceIdentifierFactory()
    {
        $model = $this->createModel();
        $resourceType = 'dummy';
        $expected = new ResourceIdentifierFactory($model, $resourceType);

        $factory = new RelationshipFactory();
        $factory->setGenerator(new Generator());

        PHPUnit::assertEmpty($factory->getData());

        $obj = $factory->setData($expected, $resourceType);

        PHPUnit::assertSame($expected, $factory->getData());
        PHPUnit::assertSame($obj, $factory);
    }

    /**
     * @test
     */
    public function setDataWithSingleModel()
    {
        $model = $this->createModel();
        $resourceType = 'dummy';

        $factory = new RelationshipFactory();
        $factory->setGenerator(new Generator());

        PHPUnit::assertEmpty($factory->getData());

        $factory->setData($model, $resourceType);

        $data = $factory->getData();

        PHPUnit::assertInstanceOf(ResourceIdentifierFactory::class, $data);
        PHPUnit::assertSame($model, $data->getModel());
    }

    /**
     * @test
     */
    public function setDataWithCollection()
    {
        $collection = $this->createCollection();
        $resourceType = 'dummy';

        $factory = new RelationshipFactory();
        $factory->setGenerator(new Generator());

        PHPUnit::assertEmpty($factory->getData());

        $factory->setData($collection, $resourceType);

        $data = $factory->getData();

        PHPUnit::assertInstanceOf(CollectionFactory::class, $data);
        $data->each(function ($rif) use ($collection) {
            PHPUnit::assertInstanceOf(ResourceIdentifierFactory::class, $rif);
            $model = $collection->first(
                function ($item) use ($rif) {
                    return $item === $rif->getModel();
                },
                null
            );
            PHPUnit::assertNotNull($model);
        });
    }
}
