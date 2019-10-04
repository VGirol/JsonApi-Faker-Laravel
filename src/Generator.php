<?php

namespace VGirol\JsonApiFaker\Laravel;

use Illuminate\Support\Collection;
use VGirol\JsonApiFaker\Contract\RelationshipContract;
use VGirol\JsonApiFaker\Exception\JsonApiFakerException;
use VGirol\JsonApiFaker\Generator as BaseGenerator;
use VGirol\JsonApiFaker\Laravel\Contract\GeneratorContract;
use VGirol\JsonApiFaker\Laravel\Contract\ResourceIdentifierContract;
use VGirol\JsonApiFaker\Laravel\Contract\ResourceObjectContract;
use VGirol\JsonApiFaker\Laravel\Contract\RiCollectionContract;
use VGirol\JsonApiFaker\Laravel\Contract\RoCollectionContract;
use VGirol\JsonApiFaker\Laravel\Factory\RelationshipFactory;
use VGirol\JsonApiFaker\Laravel\Factory\ResourceIdentifierFactory;
use VGirol\JsonApiFaker\Laravel\Factory\ResourceObjectFactory;
use VGirol\JsonApiFaker\Laravel\Factory\RiCollectionFactory;
use VGirol\JsonApiFaker\Laravel\Factory\RoCollectionFactory;

/**
 * This class extends the \VGirol\JsonApiFaker\Generator class.
 */
class Generator extends BaseGenerator implements GeneratorContract
{
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
     * @return RiCollectionContract
     * @throws JsonApiFakerException
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
     * @return RoCollectionContract
     * @throws JsonApiFakerException
     */
    public function roCollection($collection, ?string $resourceType)
    {
        return $this->create('ro-collection')->setCollection($collection, $resourceType);
    }

    /**
     * @return ResourceIdentifierContract
     * @throws JsonApiFakerException
     */
    public function resourceIdentifier(...$args)
    {
        return parent::resourceIdentifier(...$args);
    }

    /**
     * @return ResourceObjectContract
     * @throws JsonApiFakerException
     */
    public function resourceObject(...$args)
    {
        return parent::resourceObject(...$args);
    }

    /**
     * @return RelationshipContract
     * @throws JsonApiFakerException
     */
    public function relationship(...$args)
    {
        return parent::relationship(...$args);
    }
}
