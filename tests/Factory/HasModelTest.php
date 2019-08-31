<?php

namespace VGirol\JsonApiFaker\Laravel\Tests\Factory;

use PHPUnit\Framework\Assert as PHPUnit;
use VGirol\JsonApiFaker\Laravel\Factory\HasModel;
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
    public function getKey()
    {
        $model = $this->createModel();

        $mock = $this->getMockForTrait(HasModel::class);
        $mock->setModel($model);

        PHPUnit::assertEquals($model->getKey(), $mock->getKey());
    }
}
