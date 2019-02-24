<?php

namespace VGirol\JsonApiAssert\Response;

use PHPUnit\Framework\Assert as PHPUnit;

trait JsonApiAssertResponseErrors
{
    public static function assertResponseErrorObjectEquals($expected, $error)
    {
        foreach ($expected as $key => $value) {
            PHPUnit::assertArrayHasKey($key, $error);
            PHPUnit::assertEquals($value, $error[$key]);
        }
    }
}
