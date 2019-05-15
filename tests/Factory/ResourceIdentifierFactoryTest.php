<?php
namespace VGirol\JsonApiAssert\Laravel\Tests\Factory;

use PHPUnit\Framework\Assert as PHPUnit;
use VGirol\JsonApiAssert\Laravel\Factory\HelperFactory;
use VGirol\JsonApiAssert\Laravel\Tests\TestCase;

class ResourceIdentifierFactoryTest extends TestCase
{
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

        $model = $this->createModel();
        $expected = $this->createResource($model, true, null, [
            'meta' => [
                $key => $value
            ]
        ]);

        $factory = HelperFactory::create('resource-identifier', $model, $this->resourceType);
        $factory->addToMeta($key, $value);

        $result = $factory->toArray();

        PHPUnit::assertSame($expected, $result);
    }
}
