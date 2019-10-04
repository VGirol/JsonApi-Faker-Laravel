<?php

namespace VGirol\JsonApiFaker\Laravel\Factory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use VGirol\JsonApiFaker\Exception\JsonApiFakerException;
use VGirol\JsonApiFaker\Laravel\Messages;

/**
 * Factory for collection of resource identifier (@see ResourceIdentifierFactory).
 */
class RiCollectionFactory extends CollectionFactory
{
    /**
     * Returns an array of ResourceIdentifierContract.
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
             * @param \Illuminate\Database\Eloquent\Model $model
             *
             * @return \VGirol\JsonApiFaker\Laravel\Contract\ResourceIdentifierContract
             * @throws JsonApiFakerException
             */
            function ($model) use ($resourceType) {
                if (!is_a($model, Model::class)) {
                    throw new JsonApiFakerException(Messages::ERROR_NOT_MODEL_INSTANCE);
                }

                return $this->generator->resourceIdentifier($model, $resourceType);
            }
        )->toArray();
    }
}
