<?php
namespace VGirol\JsonApiAssert\Laravel\Tests\Factory;

use PHPUnit\Framework\Assert as PHPUnit;
use VGirol\JsonApiAssert\Laravel\Factory\HelperFactory;
use VGirol\JsonApiAssert\Laravel\Tests\TestCase;
use VGirol\JsonApiAssert\Laravel\Tests\Tools\Models\ModelForTest;

class ResourceObjectFactoryTest extends TestCase
{
    use CanCreate;

    /**
     * @test
     */
    public function resourceObjectFactory()
    {
        $type = 'test';

        list($model, $expected) = $this->modelFactory($type, false);

        $factory = HelperFactory::create('resource-object', $model, $type);

        $result = $factory->toArray();

        PHPUnit::assertSame($expected, $result);
    }

    /**
     * @test
     */
    public function resourceObjectFactoryWithRelationships()
    {
        $type = 'test';
        list($model, $expected) = $this->modelFactory($type, false);

        $count = 5;
        $rel_type = 'test2';
        $name = 'related';
        list($collection, $rel_expected) = $this->collectionFactory($count, $rel_type, true);
        $relationship = HelperFactory::create('relationship');
        $relationship->setData($collection, $rel_type);

        $expected['relationships'] = [
            $name => [
                'data' => $rel_expected
            ]
        ];

        $factory = HelperFactory::create('resource-object', $model, $type);
        $factory->loadRelationship($name);

        $result = $factory->toArray();

        PHPUnit::assertSame($expected, $result);
    }
}
