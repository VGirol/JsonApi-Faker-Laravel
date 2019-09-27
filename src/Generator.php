<?php

namespace VGirol\JsonApiFaker\Laravel;

use Illuminate\Support\Collection;
use VGirol\JsonApiFaker\Generator as BaseGenerator;
use VGirol\JsonApiFaker\Laravel\Factory\RelationshipFactory;
use VGirol\JsonApiFaker\Laravel\Factory\ResourceIdentifierFactory;
use VGirol\JsonApiFaker\Laravel\Factory\ResourceObjectFactory;
use VGirol\JsonApiFaker\Laravel\Factory\RiCollectionFactory;
use VGirol\JsonApiFaker\Laravel\Factory\RoCollectionFactory;

/**
 * {@inheritdoc}
 */
class Generator extends BaseGenerator
{
    /**
     * {@inheritdoc}
     */
    public function __construct()
    {
        parent::__construct();

        $this->setFactory('ri-collection', RiCollectionFactory::class)
            ->setFactory('ro-collection', RoCollectionFactory::class)
            ->setFactory('relationship', RelationshipFactory::class)
            ->setFactory('resource-identifier', ResourceIdentifierFactory::class)
            ->setFactory('resource-object', ResourceObjectFactory::class);
    }

    /**
     * Create a resource identifier collection factory.
     *
     * @param Collection  $collection
     * @param string|null $resourceType
     *
     * @return RiCollectionFactory
     */
    public function riCollection($collection, ?string $resourceType)
    {
        return $this->create('ri-collection')->setCollection($collection, $resourceType);
    }

    /**
     * Create a resource object collection factory.
     *
     * @param Collection  $collection
     * @param string|null $resourceType
     *
     * @return RoCollectionFactory
     */
    public function roCollection($collection, ?string $resourceType)
    {
        return $this->create('ro-collection')->setCollection($collection, $resourceType);
    }
}
