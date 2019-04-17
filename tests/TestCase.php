<?php

namespace VGirol\JsonApiAssert\Laravel\Tests;

use Illuminate\Database\Eloquent\Collection;
use Orchestra\Testbench\TestCase as BaseTestCase;
use PHPUnit\Framework\ExpectationFailedException;
use VGirol\JsonApiAssert\Laravel\HeaderTrait;
use VGirol\JsonApiAssert\Laravel\JsonApiAssertServiceProvider;
use VGirol\JsonApiAssert\Laravel\Tests\Tools\Models\ModelForTest;

abstract class TestCase extends BaseTestCase
{
    use HeaderTrait;

    /**
     * Load package service provider
     *
     * @param  \Illuminate\Foundation\Application $app
     * @return VGirol\JsonApiAssert\Laravel\JsonApiAssertServiceProvider
     */
    protected function getPackageProviders($app)
    {
        return [
            JsonApiAssertServiceProvider::class
        ];
    }

    protected function setFailureException($msg)
    {
        $this->expectException(ExpectationFailedException::class);
        if (!is_null($msg)) {
            $this->expectExceptionMessage($msg);
        }
    }

    protected function setInvalidArgumentException(int $arg, string $type, $value = null)
    {
        $this->expectException(\PHPUnit\Framework\Exception::class);
        $this->expectExceptionMessageRegExp(
            \sprintf(
                '/Argument #%d%sof %s::%s\(\) must be a %s/',
                $arg,
                '[\s\S]*',
                '.*',
                '.*',
                \preg_quote($type)
            )
        );
    }

    protected function createCollection($count = 5)
    {
        $collection = new Collection();
        for ($i = 1; $i <= $count; $i++) {
            $collection->push($this->createModel($i));
        }

        return $collection;
    }

    protected function createModel($i = 0)
    {
        $attributes = [
            'TST_ID' => 10 + $i,
            'TST_NAME' => 'test' . $i,
            'TST_NUMBER' => 1000 * $i + 123,
            'TST_CREATION_DATE' => null
        ];

        $model = new ModelForTest();
        $model->setRawAttributes($attributes);

        return $model;
    }

    protected function createResourceCollection(Collection $collection, $isResourceIdentifier, $withError = false)
    {
        $data = [];
        $index = rand(1, $collection->count() - 1);
        foreach ($collection as $i => $model) {
            array_push(
                $data,
                $this->createResource(
                    $model,
                    $isResourceIdentifier,
                    ($withError && ($i == $index))
                )
            );
        }

        return $data;
    }

    protected function createResource($model, $isResourceIdentifier, $withError = false, $additional = null)
    {
        $resource = [
            'type' => $model->getResourceType(),
            'id' => strval($model->getKey())
        ];
        if (!$isResourceIdentifier) {
            $resource['attributes'] = $model->toArray();
        }
        if ($withError) {
            $resource['id'] = strval($model->getKey() + 10);
        }
        if (!is_null($additional)) {
            $resource = array_merge($resource, $additional);
        }

        return $resource;
    }
}
