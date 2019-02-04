<?php
namespace VGirol\JsonApi\Tools\Assert;

use PHPUnit\Framework\Assert as PHPUnit;

trait JsonApiAssertResource
{
    public static function assertValidResourceIdentifierObject($resource, $model)
    {
        PHPUnit::assertEquals(
            $model->getResourceType(),
            $resource['type']
        );

        PHPUnit::assertEquals(
            $model->getKey(),
            $resource['id']
        );
    }

    public static function assertValidResourceObject($resource, $model)
    {
        static::assertValidResourceIdentifierObject($resource, $model);

        static::assertHasAttributes($resource);
        PHPUnit::assertEquals(
            $model->getAttributes(),
            $resource['attributes']
        );

        static::assertHasLinks($resource);
    }
}
