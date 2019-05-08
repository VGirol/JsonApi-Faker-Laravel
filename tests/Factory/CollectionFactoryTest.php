<?php
namespace VGirol\JsonApiAssert\Laravel\Tests\Factory;

use PHPUnit\Framework\Assert as PHPUnit;
use VGirol\JsonApiAssert\Laravel\Factory\HelperFactory;
use VGirol\JsonApiAssert\Laravel\Tests\TestCase;

class CollectionFactoryTest extends TestCase
{
    use CanCreate;

    /**
     * @test
     */
    public function noCollection()
    {
        $expected = null;

        $factory = HelperFactory::create('collection', null, null);

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

        $factory = HelperFactory::create('collection', $collection, null);

        $result = $factory->toArray();

        PHPUnit::assertSame($expected, $result);
    }

    /**
     * @test
     * @dataProvider resourceCollectionProvider
     */
    public function resourceCollection($isRI)
    {
        $type = 'test';
        $count = 5;
        list($collection, $expected) = $this->collectionFactory($count, $type, $isRI);

        $factory = HelperFactory::create('collection', $collection, $type, $isRI);

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
