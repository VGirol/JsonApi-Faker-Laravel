<?php

namespace VGirol\JsonApiFaker\Laravel\Tests;

use PHPUnit\Framework\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CanCreateFake;
}
