<?php

namespace VGirol\JsonApiFaker\Laravel\Factory;

use Illuminate\Support\Collection;
use VGirol\JsonApiFaker\Factory\CollectionFactory as BaseFactory;
use VGirol\JsonApiFaker\Factory\HasResourceType;
use VGirol\JsonApiFaker\Laravel\Generator;

/**
 * @inheritDoc
 */
class CollectionFactory extends BaseFactory
{
    use HasRouteName;
    use HasResourceType;

    const NO_RELATIONSHIPS_ON_RESOURCE_IDENTIFIER = 'No relationships allowed in resource identifier !';

    /**
     * A collection of models
     *
     * @var Collection|null
     */
    public $collection;

    /**
     * Indicates if this is a resource identifiers collection or not
     *
     * @var boolean
     */
    public $isResourceIdentifier;

    /**
     * Class constructor
     *
     * @param Collection|array|null $collection
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
     * Set or get the "isResourceIdentifier" flag
     *
     * @param boolean|null $isRI
     *
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
     * Set the collection
     *
     * @param Collection|array|null $collection
     *
     * @return static
     */
    public function setCollection($collection)
    {
        $this->collection = is_array($collection) ? collect($collection) : $collection;

        parent::setCollection($this->prepareCollection($collection));

        return $this;
    }

    /**
     * Add a relationship to the resource object
     *
     * @param array $relationships
     *
     * @return static
     * @throws \Exception
     */
    public function appendRelationships(array $relationships)
    {
        if ($this->isResourceIdentifier()) {
            throw new \Exception(static::NO_RELATIONSHIPS_ON_RESOURCE_IDENTIFIER);
        }

        $this->each(function ($resFactory) use ($relationships) {
            $resFactory->appendRelationships($relationships);
        });

        return $this;
    }

    /**
     * Returns a collection of resource identifier or resource object factories
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
                    $this->isResourceIdentifier() ? 'resourceIdentifier' : 'resourceObject',
                    $model,
                    $this->resourceType
                );
            }
        );
    }

    /**
     * Undocumented function
     *
     * @param [type] $func
     * @param [type] ...$args
     * @return BaseFactory
     */
    private function resourceFactory($func, ...$args)
    {
        if (!$this->isResourceIdentifier) {
            $args = array_merge($args, [$this->routeName]);
        }

        return Generator::getInstance()->{$func}(...$args);
    }

    /**
     * Undocumented function
     *
     * @param [type] $collection
     * @return void
     */
    private function prepareCollection($collection)
    {
        if (is_array($collection)) {
            return $collection;
        }

        $array = $this->transform();
        if (!is_null($array)) {
            $array = $array->toArray();
        }

        return $array;
    }
}
