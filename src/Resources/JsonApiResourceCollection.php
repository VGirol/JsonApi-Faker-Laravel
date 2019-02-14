<?php

namespace VGirol\JsonApi\Resources;

use VGirol\JsonApi\Resources\JsonApiResourceTrait;
use Illuminate\Http\Resources\Json\ResourceCollection;

class JsonApiResourceCollection extends ResourceCollection
{
    use JsonApiResourceTrait;

    public function __construct($resource)
    {
        parent::__construct($resource);

        $this->withoutWrapping();
    }

    // public function with($request)
    // {
    //     return [
    //         'links'    => [
    //             'self' => route($this->getResourceType() . '.index'),
    //         ],
    //     ];
    // }
}
