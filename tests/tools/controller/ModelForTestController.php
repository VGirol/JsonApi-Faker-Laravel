<?php

namespace VGirol\JsonApi\Tests\Tools\Controller;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;
use VGirol\JsonApi\Controllers\JsonApiRestTrait;
use VGirol\JsonApi\Tests\Tools\Models\ModelForTest;
use VGirol\JsonApi\Tests\Tools\Models\RelatedModelForTest;
use VGirol\JsonApi\Tests\Tools\Requests\ModelForTestFormRequest;
use VGirol\JsonApi\Tests\Tools\Resources\ModelForTestResource;
use VGirol\JsonApi\Tests\Tools\Resources\ModelForTestResourceCollection;
use VGirol\JsonApi\Tests\Tools\Resources\RelatedModelForTestResource;
use VGirol\JsonApi\Tests\Tools\Resources\RelatedModelForTestResourceCollection;

class ModelForTestController extends BaseController
{
    use JsonApiRestTrait;

    protected function getDictionary()
    {
        return [
            'primary' => [
                'model' => ModelForTest::class,
                'resource' => ModelForTestResource::class,
                'resource-collection' => ModelForTestResourceCollection::class
            ],
            'relatonships' => [
                'related' => [
                    'model' => RelatedModelForTest::class,
                    'resource' => RelatedModelForTestResource::class,
                    'resource-collection' => RelatedModelForTestResourceCollection::class
                ]
            ]
        ];
    }

    public function store(ModelForTestFormRequest $request): JsonResponse
    {
        return $this->storeObject($request);
    }

    public function update(ModelForTestFormRequest $request, $id): JsonResponse
    {
        return $this->updateObject($request, $id);
    }
}
