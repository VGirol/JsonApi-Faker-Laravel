<?php

namespace VGirol\JsonApiFaker\Laravel\Tests;

use PHPUnit\Framework\TestCase as BaseTestCase;
use VGirol\JsonApiFaker\Laravel\Testing\CanCreateFake;

abstract class TestCase extends BaseTestCase
{
    use CanCreateFake;
}
