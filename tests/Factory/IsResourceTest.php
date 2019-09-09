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

        PHPUnit::assertSame($model, $mock->model);
        PHPUnit::assertEquals($model->getKey(), $mock->id);
        PHPUnit::assertEquals($resourceType, $mock->resourceType);
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

        PHPUnit::assertNull($mock->model);
        PHPUnit::assertNull($mock->resourceType);
        PHPUnit::assertNull($mock->id);

        $model = $this->createModel();
        $resourceType = 'dummy';

        $mock->setValues($model, $resourceType);

        PHPUnit::assertEquals($model, $mock->model);
        PHPUnit::assertEquals($resourceType, $mock->resourceType);
        PHPUnit::assertEquals($model->getKey(), $mock->id);
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
