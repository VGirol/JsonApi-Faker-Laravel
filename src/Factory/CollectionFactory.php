<?php

namespace VGirol\JsonApiAssert\Laravel\Factory;

use Illuminate\Support\Collection;
use VGirol\JsonApiAssert\Factory\CollectionFactory as BaseFactory;

class CollectionFactory extends BaseFactory
{
    /**
     * Undocumented variable
     *
     * @var \Illuminate\Support\Collection
     */
    protected $collection;

    protected $isResourceIdentifier;

    protected $resourceType;

    public function __construct($collection, $resourceType, $isRI = false)
    {
        $this->setResourceType($resourceType)
            ->isResourceIdentifier($isRI)
            ->setCollection($collection);
    }

    public function setResourceType(?string $type): self
    {
        $this->resourceType = $type;

        return $this;
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
     * @param array|\Illuminate\Support\Collection $collection
     * @return static
     */
    public function setCollection($collection)
    {
        $base = is_array($collection) ? collect($collection) : $collection;
        $this->collection = $base;

        if (is_array($collection)) {
            $array = $collection;
        } else {
            $array = $this->transform();
            if (!is_null($array)) {
                $array = $array->toArray();
            }
        }
        parent::setCollection($array);

        return $this;
    }

    protected function transform(): ?Collection
    {
        if (is_null($this->collection)) {
            return null;
        }

        return $this->collection->map(
            function ($model) {
                return $this->resourceFactory(
                    $this->isResourceIdentifier() ? 'resource-identifier' : 'resource-object',
                    $model,
                    $this->resourceType
                );
            }
        );
    }

    protected function resourceFactory(string $type, ...$args)
    {
        return HelperFactory::create($type, ...$args);
    }

    public function appendRelationships(array $relationships): self
    {
        if ($this->isResourceIdentifier()) {
            return $this;
        }

        foreach ($relationships as $name => $resourceType) {
            $this->each(function ($resource) use ($name, $resourceType) {
                $resource->loadRelationship($name, $resourceType);
            });
        }

        return $this;
    }

    // protected function roFactory($model, $resourceType)
    // {
    //     return $this->factory(ResourceObjectFactory::class, [$model, $resourceType]);
    // }

    // protected function riFactory($model, $resourceType)
    // {
    //     return $this->factory(ResourceIdentifierFactory::class, [$model, $resourceType]);
    // }
}
