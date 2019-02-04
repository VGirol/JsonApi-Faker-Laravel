<?php

namespace VGirol\JsonApi\Tests\Tools\Controller;

use Illuminate\Http\JsonResponse;
use VGirol\JsonApi\Controllers\JsonApiRestTrait;
use Illuminate\Routing\Controller as BaseController;
//use Illuminate\Foundation\Validation\ValidatesRequests;

class ModelForTestController extends BaseController
{
//    use ValidatesRequests;
    use JsonApiRestTrait;

    /**
     * For test purpose
     */
    protected function modelNamespace(): string
    {
        return '\\VGirol\\JsonApi\\Tests\\Tools\\Models\\';
    }

    /**
     * For test purpose
     */
    protected function resourceNamespace(): string
    {
        return '\\VGirol\\JsonApi\\Tests\\Tools\\Resources\\';
    }

    public function store(VehicleFormRequest $request): JsonResponse
    {
        return $this->storeObject($request);
    }

    public function update(VehicleFormRequest $request, $id): JsonResponse
    {
        return $this->updateObject($request, $id);
    }
}
