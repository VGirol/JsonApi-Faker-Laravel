<?php

namespace VGirol\JsonApiAssert\Laravel\Asserts;

trait AssertHelpers
{
    private static function getJsonFromPath($json, $path)
    {
        $path = explode('.', $path);
        foreach ($path as $member) {
            static::assertHasMember($member, $json);
            $json = $json[$member];
        }

        return $json;
    }
}
