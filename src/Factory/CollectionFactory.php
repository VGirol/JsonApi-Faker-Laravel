<?php

namespace VGirol\JsonApiAssert\Laravel\Factory;

use Illuminate\Support\Collection;
use VGirol\JsonApiAssert\Factory\CollectionFactory as BaseFactory;
use VGirol\JsonApiAssert\Factory\HasResourceType;

class CollectionFactory extends BaseFactory
{
    use HasRouteName;
    use HasResourceType;

    /**
     * Undocumented variable
     *
     * @var Collection
     */
    protected $collection;

    /**
     * Undocumented variable
     *
     * @var boolean
     */
    protected $isResourceIdentifier;

    /**
     * Undocumented function
     *
     * @param Collection|null $collection
     * @param string|null $resourceType
     * @param string|null $routeName
     * @param boolean $isRI
     */
    public function __construct($collection, ?string $resourceType, ?string $routeName, $isRI = false)
    {
        $this->isResourceIdentifier($isRI);
        $this->setResourceType($resourceType)
            ->setRouteName($routeName)
            ->setCollection($collection);
    }

    /**
     * Undocumented function
     *
     * @param boolean|null $isRI
     * @return boolean|static
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
     * @param Collection|array $collection
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

    /**
     * Undocumented function
     *
     * @return Collection|null
     */
    protected function transform(): ?Collection
    {
        if (is_null($this->collection)) {
            return null;
        }

        return $this->collection->map(
            function ($model) {
                return $this->resourceFactory(
                    $this->isResourceIdentifier() ? 'createResourceIdentifier' : 'createResourceObject',
                    $model,
                    $this->resourceType
                );
            }
        );
    }

    protected function resourceFactory($func, ...$args)
    {
        if (!$this->isResourceIdentifier) {
            $args = array_merge($args, [$this->routeName]);
        }

        return call_user_func_array([$this->getHelperClassName(), $func], $args);
    }

    protected function getHelperClassName(): string
    {
        return HelperFactory::class;
    }

    /**
     * Undocumented function
     *
     * @param array $relationships
     * @return static
     */
    public function appendRelationships(array $relationships)
    {
        if ($this->isResourceIdentifier()) {
            return $this;
        }

        $this->each(function ($resource) use ($relationships) {
            $resource->appendRelationships($relationships);
        });

        return $this;
    }
}
