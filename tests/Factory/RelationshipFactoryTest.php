<?php

namespace VGirol\JsonApiFaker\Laravel\Tests\Factory;

use PHPUnit\Framework\Assert as PHPUnit;
use VGirol\JsonApiFaker\Laravel\Factory\CollectionFactory;
use VGirol\JsonApiFaker\Laravel\Factory\RelationshipFactory;
use VGirol\JsonApiFaker\Laravel\Factory\ResourceIdentifierFactory;
use VGirol\JsonApiFaker\Laravel\Tests\TestCase;

class RelationshipFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function constructor()
    {
        $name = 'test';
        $factory = new RelationshipFactory($name);

        PHPUnit::assertEquals($name, $factory->name);
    }

    /**
     * @test
     */
    public function setDataWithSingleResourceIdentifierFactory()
    {
        $name = 'related';
        $model = $this->createModel();
        $resourceType = 'dummy';
        $expected = new ResourceIdentifierFactory($model, $resourceType);

        $factory = new RelationshipFactory($name);

        PHPUnit::assertEmpty($factory->data);

        $obj = $factory->setData($expected);

        PHPUnit::assertSame($expected, $factory->data);
        PHPUnit::assertSame($obj, $factory);
    }

    /**
     * @test
     */
    public function setDataWithSingleResource()
    {
        $name = 'related';
        $model = $this->createModel();

        $factory = new RelationshipFactory($name);
        PHPUnit::assertEmpty($factory->data);

        $factory->setData($model);

        $data = $factory->data;

        PHPUnit::assertInstanceOf(ResourceIdentifierFactory::class, $data);
        PHPUnit::assertSame($model, $data->model);
    }

    /**
     * @test
     */
    public function setDataWithCollection()
    {
        $name = 'related';
        $collection = $this->createCollection();

        $factory = new RelationshipFactory($name);
        PHPUnit::assertEmpty($factory->data);

        $factory->setData($collection);

        $data = $factory->data;

        PHPUnit::assertInstanceOf(CollectionFactory::class, $data);
        $data->each(function ($rif) use ($collection) {
            PHPUnit::assertInstanceOf(ResourceIdentifierFactory::class, $rif);
            $model = $collection->first(
                function ($item) use ($rif) {
                    return $item === $rif->model;
                },
                null
            );
            PHPUnit::assertNotNull($model);
        });
    }
}
