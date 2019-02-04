<?php

namespace VGirol\JsonApi\Resources;

use Illuminate\Support\Facades\Route;
use VGirol\JsonApi\Tools\ClassNameTools;
use VGirol\JsonApi\Exceptions\JsonApiException;
use VGirol\JsonApi\Models\JsonApiModelInterface;
use Illuminate\Http\Resources\Json\JsonResource;

class JsonApiResource extends JsonResource
{
    use ClassNameTools;
    use JsonApiResourceTrait;

    private $relationships = [];

    /**
     * Create a new resource instance.
     *
     * @param  VGirol\JsonApi\Models\JsonApiModelInterface  $resource
     * @return void
     */
    public function __construct(JsonApiModelInterface $resource)
    {
        parent::__construct($resource);

        $this->withoutWrapping();
    }

    /**
     * Undocumented function
     *
     * @param closure $fn
     * @return void
     */
    public function addRelationship($fn)
    {
        array_push($this->relationships, $fn);
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request) : array
    {
        $res = [
            'type' => $this->getResourceType(),
            'id' => (string)$this->getId()
        ];

        // Is resource object : add attributes, links & relationships
        if ($this->isResourceObject()) {
            $res['attributes'] = $this->getAttributes();
            $res['links'] = [
                'self' => $this->getLocation()
            ];

            foreach ($this->relationships as $fn) {
                $add = call_user_func($fn);
                foreach ($add as $key => $value) {
                    $res['relationships'][$key] = [
                        'data' => $value->toArray($request)
                    ];
                }
            }
        }

        return $res;
    }

    public function getLocation()
    {
        $routeName = $this->getResourceType() . '.show';

        return Route::has($this->getResourceType() . '.show') ? route($routeName, [$this->getResourceType() => $this->getId()]) : null;
    }

    protected function getId() : int
    {
        return $this->resource->getKey();
    }

    protected function getResourceType() : string
    {
        return $this->resource->getResourceType();
    }
}
