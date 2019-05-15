<?php
namespace VGirol\JsonApiAssert\Laravel\Tests\Factory;

use PHPUnit\Framework\Assert as PHPUnit;
use VGirol\JsonApiAssert\Laravel\Factory\HelperFactory;
use VGirol\JsonApiAssert\Laravel\Tests\TestCase;

class CollectionFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function noCollection()
    {
        $expected = null;

        $factory = HelperFactory::create('collection', null, null, null);

        $result = $factory->toArray();

        PHPUnit::assertSame($expected, $result);
    }

    /**
     * @test
     */
    public function emptyCollection()
    {
        $expected = [];
        $collection = [];

        $factory = HelperFactory::create('collection', $collection, $this->resourceType, $this->routeName);

        $result = $factory->toArray();

        PHPUnit::assertSame($expected, $result);
    }

    /**
     * @test
     * @dataProvider resourceCollectionProvider
     */
    public function resourceCollection($isRI)
    {
        $count = 5;
        $collection = $this->createCollection($count);
        $expected = $this->createResourceCollection($collection, $isRI, null);

        $factory = HelperFactory::create('collection', $collection, $this->resourceType, $this->routeName, $isRI);

        $result = $factory->toArray();

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
}
