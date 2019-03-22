<?php

namespace VGirol\JsonApiAssert\Laravel\Asserts;

use VGirol\JsonApiAssert\Assert as JsonApiAssert;

trait AssertHelpers
{
    public static function getJsonFromPath($json, $path)
    {
        $path = explode('.', $path);
        foreach ($path as $member) {
            JsonApiAssert::assertHasMember($json, $member);
            $json = $json[$member];
        }

        return $json;
    }
}
