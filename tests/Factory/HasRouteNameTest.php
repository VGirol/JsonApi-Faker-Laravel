<?php

namespace VGirol\JsonApiFaker\Laravel\Tests\Factory;

use PHPUnit\Framework\Assert as PHPUnit;
use VGirol\JsonApiFaker\Laravel\Factory\HasRouteName;
use VGirol\JsonApiFaker\Laravel\Tests\TestCase;
use VGirol\JsonApiFaker\Testing\CheckMethods;

class HasRouteNameTest extends TestCase
{
    use CheckMethods;

    /**
     * @test
     */
    public function setRouteName()
    {
        $this->checkSetMethod(
            $this->getMockForTrait(HasRouteName::class),
            'setRouteName',
            'routeName',
            'first',
            'second'
        );
    }

    /**
     * @test
     */
    public function setRouteNameWithNullValue()
    {
        $mock = $this->getMockForTrait(HasRouteName::class);
        $mock->setRouteName(null);

        PHPUnit::assertNull($mock->routeName);
    }
}
