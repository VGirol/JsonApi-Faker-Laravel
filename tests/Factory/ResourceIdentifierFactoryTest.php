<?php
namespace VGirol\JsonApiAssert\Laravel\Tests\Factory;

use PHPUnit\Framework\Assert as PHPUnit;
use VGirol\JsonApiAssert\Laravel\Factory\HelperFactory;
use VGirol\JsonApiAssert\Laravel\Tests\TestCase;

class ResourceIdentifierFactoryTest extends TestCase
{
    use CanCreate;

    /**
     * @test
     */
    public function resourceIdentifierFactoryEmpty()
    {
        $expected = null;

        $factory = HelperFactory::create('resource-identifier', null, null);

        PHPUnit::assertEquals('VGirol\JsonApiAssert\Laravel\Factory\ResourceIdentifierFactory', get_class($factory));

        $result = $factory->toArray();

        PHPUnit::assertSame($expected, $result);
    }

    /**
     * @test
     */
    public function resourceIdentifierFactory()
    {
        $key = 'key';
        $value = 'value';
        $type = 'test';

        list($model, $expected) = $this->modelFactory($type, true);
        $expected['meta'] = [
            $key => $value
        ];

        $factory = HelperFactory::create('resource-identifier', $model, $type);
        $factory->addToMeta($key, $value);

        $result = $factory->toArray();

        PHPUnit::assertSame($expected, $result);
    }
}
