<?php

namespace VGirol\JsonApiAssert\Laravel\Asserts;

/**
 * Provides some helpers
 */
trait AssertHelpers
{
    /**
     * Gets a fragment of a json document.
     *
     * @param array $json
     * @param string $path
     * @return array
     */
    protected static function getJsonFromPath($json, $path): array
    {
        $path = explode('.', $path);
        foreach ($path as $member) {
            static::assertHasMember($member, $json);
            $json = $json[$member];
        }

        return $json;
    }
}
