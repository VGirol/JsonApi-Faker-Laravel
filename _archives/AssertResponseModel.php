<?php

namespace VGirol\JsonApiAssert\Laravel\Asserts;

use VGirol\JsonApiAssert\Assert as JsonApiAssert;
use PHPUnit\Framework\Assert as PHPUnit;

trait AssertResponseModel
{
    public static function assertResourceIdentifierObjectEqualsModel($model, $resource)
    {
        static::checkIfImplementsJsonApiModelInterface($model);

        PHPUnit::assertEquals(
            $model->getResourceType(),
            $resource['type']
        );

        PHPUnit::assertEquals(
            $model->getKey(),
            $resource['id']
        );
    }

    public static function assertResourceObjectEqualsModel($model, $resource)
    {
        static::checkIfImplementsJsonApiModelInterface($model);

        static::assertResourceIdentifierObjectEqualsModel($model, $resource);

        JsonApiAssert::assertHasAttributes($resource);
        PHPUnit::assertEquals(
            $model->getAttributes(),
            $resource['attributes']
        );

        JsonApiAssert::assertHasLinks($resource);
    }

    private static function checkIfImplementsJsonApiModelInterface($model)
    {
        if (!method_exists($model, 'getResourceType')) {
            throw new \Exception('Model object must implement "getResourceType" method.');
        }
        if (!method_exists($model, 'getKey')) {
            throw new \Exception('Model object must implement "getKey" method.');
        }
    }
}
