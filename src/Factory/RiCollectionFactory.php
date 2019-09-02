<?php

namespace VGirol\JsonApiFaker\Laravel\Factory;

use Illuminate\Support\Collection;
use VGirol\JsonApiFaker\Laravel\Generator;

/**
 * Factory for collection of resource identifier (@see ResourceIdentifierFactory).
 */
class RiCollectionFactory extends CollectionFactory
{
    /**
     * Returns a collection of resource identifier or resource object factories
     *
     * @param Collection $collection
     * @param string $resourceType
     *
     * @return array<ResourceObjectFactory>|array<ResourceIdentifierFactory>
     */
    protected function transform($collection, $resourceType): array
    {
        return $collection->map(
            /**
             * @param \Illuminate\Database\Eloquent\Model $model
             *
             * @return ResourceIdentifierFactory
             */
            function ($model) use ($resourceType) {
                return Generator::getInstance()->resourceIdentifier($model, $resourceType);
            }
        )->toArray();
    }
}
