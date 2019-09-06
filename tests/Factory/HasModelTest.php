<?php

namespace VGirol\JsonApiFaker\Laravel\Tests\Factory;

use PHPUnit\Framework\Assert as PHPUnit;
use VGirol\JsonApiFaker\Laravel\Factory\HasModel;
use VGirol\JsonApiFaker\Laravel\Messages;
use VGirol\JsonApiFaker\Laravel\Tests\TestCase;
use VGirol\JsonApiFaker\Testing\CheckMethods;

class HasModelTest extends TestCase
{
    use CheckMethods;

    /**
     * @test
     */
    public function setModel()
    {
        $this->checkSetMethod(
            $this->getMockForTrait(HasModel::class),
            'setModel',
            'model',
            $this->createModel(),
            $this->createModel()
        );
    }

    /**
     * @test
     */
    public function setModelToNull()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage(Messages::ERROR_MODEL_NOT_NULL);

        $mock = $this->getMockForTrait(HasModel::class);

        $mock->setModel(null);
    }

    /**
     * @test
     */
    public function getKey()
    {
        $model = $this->createModel();

        $mock = $this->getMockForTrait(HasModel::class);
        $mock->setModel($model);

        PHPUnit::assertEquals($model->getKey(), $mock->getKey());
    }

    /**
     * @test
     */
    public function getKeyFailed()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage(Messages::ERROR_MODEL_NOT_SET);

        $mock = $this->getMockForTrait(HasModel::class);

        $mock->getKey();
    }
}
