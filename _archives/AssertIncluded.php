<?php

namespace VGirol\JsonApiAssert\Laravel\Asserts;

use DMS\PHPUnitExtensions\ArraySubset\Assert as AssertArray;
use Illuminate\Foundation\Testing\TestResponse;
use PHPUnit\Framework\Assert as PHPUnit;

trait AssertIncluded
{
    public static function assertIncludedObjectContains(TestResponse $response, $expectedCollection, $expectedResourceType)
    {
        // Decode JSON response
        $json = $response->json();

        static::assertHasIncluded($json);
        $included = $json['included'];

        foreach ($expectedCollection as $expectedModel) {
            $resCollection = collect($included)->filter(function ($item) use ($expectedModel, $expectedResourceType) {
                return ($item['id'] == $expectedModel->getKey()) && ($item['type'] == $expectedResourceType);
            });

            PHPUnit::assertEquals(1, $resCollection->count());
            static::assertResourceObjectEquals($expectedModel, $expectedResourceType, $resCollection->first());
        }
    }
}
