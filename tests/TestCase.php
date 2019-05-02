<?php

namespace VGirol\JsonApiAssert\Laravel\Tests;

use Illuminate\Support\Collection;
use Orchestra\Testbench\TestCase as BaseTestCase;
use VGirol\JsonApiAssert\Laravel\HeaderTrait;
use VGirol\JsonApiAssert\Laravel\JsonApiAssertServiceProvider;
use VGirol\JsonApiAssert\Laravel\Tests\Tools\Models\ModelForTest;
use VGirol\JsonApiAssert\SetExceptionsTrait;

abstract class TestCase extends BaseTestCase
{
    use HeaderTrait;
    use SetExceptionsTrait;

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

    protected function createCollection($count = 5)
    {
        $collection = new Collection();
        for ($i = 1; $i <= $count; $i++) {
            $collection->push($this->createModel($i));
        }

        return $collection;
    }

    protected function createModel($index = 0)
    {
        $attributes = [
            'TST_ID' => 10 + $index,
            'TST_NAME' => 'test' . $index,
            'TST_NUMBER' => 1000 * $index + 123,
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

    protected function addErrorToResourceCollection($collection)
    {
        $index = rand(1, $collection->count() - 1);
        $this->addErrorToResource($collection[$index]);

        return $collection;
    }

    protected function addErrorToResource($resource)
    {
        $resource['id'] = strval(intval($resource['id']) + 10);

        return $resource;
    }
}
