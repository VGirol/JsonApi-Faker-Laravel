<?php

namespace VGirol\JsonApiFaker\Laravel\Factory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use VGirol\JsonApiFaker\Exception\JsonApiFakerException;
use VGirol\JsonApiFaker\Laravel\Messages;

/**
 * Factory for collection of resource object (@see ResourceObjectFactory).
 */
class RoCollectionFactory extends CollectionFactory
{
    /**
     * Add a relationship to the resource object.
     *
     * @param array $relationships
     *
     * @return static
     * @throws JsonApiFakerException
     */
    public function appendRelationships(array $relationships)
    {
        return $this->each(
            /**
             * @param \VGirol\JsonApiFaker\Laravel\Contract\ResourceObjectContract $resFactory
             *
             * @return void
             */
            function ($resFactory) use ($relationships) {
                $resFactory->appendRelationships($relationships);
            }
        );
    }

    /**
     * Returns a collection of ResourceObjectContract.
     *
     * @param Collection $collection
     * @param string     $resourceType
     *
     * @return array
     */
    protected function transform($collection, $resourceType): array
    {
        return $collection->map(
            /**
             * @param Model $model
             *
             * @return \VGirol\JsonApiFaker\Laravel\Contract\ResourceObjectContract
             * @throws JsonApiFakerException
             */
            function ($model) use ($resourceType) {
                if (!is_a($model, Model::class)) {
                    throw new JsonApiFakerException(Messages::ERROR_NOT_MODEL_INSTANCE);
                }

                return $this->generator->resourceObject($model, $resourceType);
            }
        )->toArray();
    }
}
