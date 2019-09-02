<?php

namespace VGirol\JsonApiFaker\Laravel\Factory;

use Illuminate\Support\Collection;
use VGirol\JsonApiFaker\Factory\CollectionFactory as BaseFactory;
use VGirol\JsonApiFaker\Laravel\Messages;

/**
 * Factory for collection of resource object (@see ResourceObjectFactory)
 * or resource identifier (@see ResourceIdentifierFactory).
 */
abstract class CollectionFactory extends BaseFactory
{
    /**
     * A collection of models
     *
     * @var Collection|null
     */
    public $collection;

    /**
     * Class constructor
     *
     * @param Collection|array<ResourceObjectFactory>|array<ResourceIdentifierFactory>|null $collection
     * @param string|null $resourceType
     *
     * @throws \Exception
     */
    public function __construct($collection = null, ?string $resourceType = null)
    {
        $this->setCollection($collection, $resourceType);
    }

    /**
     * Set the collection
     *
     * @param Collection|array<ResourceObjectFactory>|array<ResourceIdentifierFactory>|null $provided
     * @param string|null $resourceType
     *
     * @return static
     * @throws \Exception
     */
    public function setCollection($provided, $resourceType = null)
    {
        $collection = null;
        $array = null;
        if (is_array($provided)) {
            $array = $provided;
            $collection = collect($provided)->map(
                /**
                 * @param ResourceObjectFactory|ResourceIdentifierFactory $item
                 *
                 * @return \Illuminate\Database\Eloquent\Model
                 * @throws \Exception
                 */
                function ($item) {
                    if (!is_a($item, ResourceObjectFactory::class) && !is_a($item, ResourceIdentifierFactory::class)) {
                        throw new \Exception(Messages::ERROR_NO_FACTORY);
                    }
                    $model = $item->model;
                    if ($model === null) {
                        throw new \Exception(Messages::ERROR_NO_MODEL);
                    }
                    return $model;
                }
            );
        }

        if (is_a($provided, Collection::class)) {
            if ($resourceType === null) {
                throw new \Exception(Messages::ERROR_TYPE_NOT_NULL);
            }

            $collection = $provided;
            $array = $this->transform($collection, $resourceType);
        }

        $this->collection = $collection;
        parent::setCollection($array);

        return $this;
    }

    /**
     * Returns a collection of resource identifier or resource object factories
     *
     * @param Collection $collection
     * @param string $resourceType
     *
     * @return array<ResourceObjectFactory>|array<ResourceIdentifierFactory>
     */
    abstract protected function transform($collection, $resourceType): array;
}
