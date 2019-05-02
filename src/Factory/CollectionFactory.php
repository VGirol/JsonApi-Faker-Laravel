<?php

namespace VGirol\JsonApiAssert\Laravel\Factory;

use Illuminate\Support\Collection;
use VGirol\JsonApiAssert\Factory\CollectionFactory as BaseFactory;
use VGirol\JsonApiAssert\Laravel\Factory\ResourceIdentifierFactory;
use VGirol\JsonApiAssert\Laravel\Factory\ResourceObjectFactory;

class CollectionFactory extends BaseFactory
{
    /**
     * Undocumented variable
     *
     * @var \Illuminate\Support\Collection
     */
    protected $baseCollection;

    protected $isResourceIdentifier;

    public function __construct($collection, $isRI = false)
    {
        $this->isResourceIdentifier($isRI)
            ->setBaseCollection($collection);
    }

    /**
     * Undocumented function
     *
     * @param boolean|null $isRI
     * @return boolean|self
     */
    public function isResourceIdentifier($isRI = null)
    {
        if (is_null($isRI)) {
            return $this->isResourceIdentifier;
        }

        $this->isResourceIdentifier = $isRI;

        return $this;
    }

    /**
     * Undocumented function
     *
     * @param \Illuminate\Support\Collection $collection
     * @return self
     */
    public function setBaseCollection($collection): self
    {
        $this->baseCollection = $collection;

        $this->setCollection(
            $this->transform()->toArray()
        );

        return $this;
    }

    protected function transform(): Collection
    {
        return $this->baseCollection->map(
            function ($model, $key) {
                if ($this->isResourceIdentifier()) {
                    $resource = $this->riFactory($model);
                } else {
                    $resource = $this->roFactory($model);
                }

                return $resource;
            }
        );
    }

    // public function appendRelationships(array $relationships): self
    // {
    //     foreach ($relationships as $name) {
    //         $this->each(function ($resource) use ($name) {
    //             if ($this->isResourceIdentifier()) {
    //                 return;
    //             }

    //             $resource->appendRelationship($name);
    //         });
    //     }

    //     return $this;
    // }

    // protected function roFactory($model, $resourceType)
    // {
    //     return $this->factory(ResourceObjectFactory::class, [$model, $resourceType]);
    // }

    // protected function riFactory($model, $resourceType)
    // {
    //     return $this->factory(ResourceIdentifierFactory::class, [$model, $resourceType]);
    // }
}
