<?php

namespace VGirol\JsonApiFaker\Laravel\Factory;

use Illuminate\Support\Collection;
use VGirol\JsonApiFaker\Laravel\Generator;

/**
 * Factory for collection of resource object (@see ResourceObjectFactory)
 */
class RoCollectionFactory extends CollectionFactory
{
    /**
     * Add a relationship to the resource object
     *
     * @param array<string,string> $relationships
     *
     * @return static
     * @throws \Exception
     */
    public function appendRelationships(array $relationships)
    {
        return $this->each(
            /**
             * @param ResourceObjectFactory $resFactory
             *
             * @return void
             */
            function ($resFactory) use ($relationships) {
                $resFactory->appendRelationships($relationships);
            }
        );
    }

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
             * @return ResourceObjectFactory
             */
            function ($model) use ($resourceType) {
                return Generator::getInstance()->resourceObject($model, $resourceType);
            }
        )->toArray();
    }
}
