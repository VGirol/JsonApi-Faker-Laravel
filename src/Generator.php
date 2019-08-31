<?php

namespace VGirol\JsonApiFaker\Laravel;

// use Illuminate\Database\Eloquent\Model;
// use Illuminate\Support\Collection;
use VGirol\JsonApiFaker\Generator as BaseGenerator;
use VGirol\JsonApiFaker\Laravel\Factory\CollectionFactory;
use VGirol\JsonApiFaker\Laravel\Factory\RelationshipFactory;
use VGirol\JsonApiFaker\Laravel\Factory\ResourceIdentifierFactory;
use VGirol\JsonApiFaker\Laravel\Factory\ResourceObjectFactory;

/**
 * @inheritDoc
 */
class Generator extends BaseGenerator
{
    private static $instance;

    /**
     * Undocumented function
     *
     * @return static
     */
    public static function getInstance()
    {
        if (static::$instance == null) {
            static::$instance = new static;
        }

        return static::$instance;
    }

    /**
     * @inheritDoc
     */
    public function __construct()
    {
        parent::__construct();

        $this->setFactory('collection', CollectionFactory::class)
            ->setFactory('relationship', RelationshipFactory::class)
            ->setFactory('resource-identifier', ResourceIdentifierFactory::class)
            ->setFactory('resource-object', ResourceObjectFactory::class);
    }

    // /**
    //  * @inheritDoc
    //  *
    //  * @param Collection $collection
    //  * @param string|null $resourceType
    //  * @param string|null $routeName
    //  * @param boolean $isRI
    //  *
    //  * @return CollectionFactory
    //  */
    // public function collection($collection, ?string $resourceType, ?string $routeName, bool $isRI = false)
    // {
    //     return $this->create('collection', $collection, $resourceType, $routeName, $isRI);
    // }

    // /**
    //  * @inheritDoc
    //  *
    //  * @param string $name
    //  *
    //  * @return RelationshipFactory
    //  */
    // public function relationship(string $name)
    // {
    //     return $this->create('relationship', $name);
    // }

    // /**
    //  * @inheritDoc
    //  *
    //  * @param Model $model
    //  * @param string|null $resourceType
    //  *
    //  * @return ResourceIdentifierFactory
    //  */
    // public function resourceIdentifier($model, ?string $resourceType)
    // {
    //     return $this->create('resource-identifier', $model, $resourceType);
    // }

    // /**
    //  * @inheritDoc
    //  *
    //  * @param Model $model
    //  * @param string $resourceType
    //  * @param string $routeName
    //  *
    //  * @return ResourceObjectFactory
    //  */
    // public function resourceObject($model, string $resourceType, string $routeName)
    // {
    //     return $this->create('resource-object', $model, $resourceType, $routeName);
    // }
}
