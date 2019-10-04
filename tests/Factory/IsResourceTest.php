<?php

namespace VGirol\JsonApiFaker\Laravel\Tests\Factory;

use PHPUnit\Framework\Assert as PHPUnit;
use VGirol\JsonApiFaker\Exception\JsonApiFakerException;
use VGirol\JsonApiFaker\Factory\HasIdentification;
use VGirol\JsonApiFaker\Laravel\Factory\IsResource;
use VGirol\JsonApiFaker\Laravel\Messages;
use VGirol\JsonApiFaker\Laravel\Tests\TestCase;

class IsResourceTest extends TestCase
{
    /**
     * @test
     */
    public function constructor()
    {
        $resourceType = 'dummy';
        $model = $this->createModel();

        $mock = new class($model, $resourceType) {
            use IsResource;
            use HasIdentification;
        };

        PHPUnit::assertSame($model, $mock->getModel());
        PHPUnit::assertEquals($model->getKey(), $mock->getId());
        PHPUnit::assertEquals($resourceType, $mock->getResourceType());
    }

    /**
     * @test
     */
    public function setValues()
    {
        $mock = new class() {
            use IsResource;
            use HasIdentification;
        };

        PHPUnit::assertNull($mock->getModel());
        PHPUnit::assertNull($mock->getResourceType());
        PHPUnit::assertNull($mock->getId());

        $model = $this->createModel();
        $resourceType = 'dummy';

        $mock->setValues($model, $resourceType);

        PHPUnit::assertEquals($model, $mock->getModel());
        PHPUnit::assertEquals($resourceType, $mock->getResourceType());
        PHPUnit::assertEquals($model->getKey(), $mock->getId());
    }

    /**
     * @test
     */
    public function setValuesFailedModelIsNull()
    {
        $this->expectException(JsonApiFakerException::class);
        $this->expectExceptionMessage(Messages::ERROR_MODEL_NOT_NULL);

        $mock = new class() {
            use IsResource;
            use HasIdentification;
        };

        $model = null;
        $resourceType = 'dummy';

        $mock->setValues($model, $resourceType);
    }
}
