<?php

namespace VGirol\JsonApi\Tests;

use VGirol\JsonApi\Tools\Assert\JsonApiTest;
use VGirol\JsonApi\Tests\JsonApiTestCommon;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    use JsonApiTest;
    use JsonApiTestCommon;
}
