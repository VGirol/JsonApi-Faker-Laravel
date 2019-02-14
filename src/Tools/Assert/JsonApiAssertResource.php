<?php
namespace VGirol\JsonApi\Tools\Assert;

use PHPUnit\Framework\Assert as PHPUnit;
use VGirol\JsonApi\Models\JsonApiModelInterface;

trait JsonApiAssertResource
{
    public static function assertResourceIdentifierObjectEqualsModel(JsonApiModelInterface $model, $resource)
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

    public static function assertResourceObjectEqualsModel(JsonApiModelInterface $model, $resource)
    {
        static::assertResourceIdentifierObjectEqualsModel($model, $resource);

        static::assertHasAttributes($resource);
        PHPUnit::assertEquals(
            $model->getAttributes(),
            $resource['attributes']
        );

        static::assertHasLinks($resource);
    }
}
