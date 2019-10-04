<?php

namespace VGirol\JsonApiFaker\Laravel\Factory;

use Illuminate\Support\Collection;
use VGirol\JsonApiFaker\Exception\JsonApiFakerException;
use VGirol\JsonApiFaker\Factory\CollectionFactory as BaseFactory;
use VGirol\JsonApiFaker\Laravel\Contract\CollectionContract;
use VGirol\JsonApiFaker\Laravel\Contract\ResourceIdentifierContract;
use VGirol\JsonApiFaker\Laravel\Contract\ResourceObjectContract;
use VGirol\JsonApiFaker\Laravel\Messages;

/**
 * Factory for collection of resource object (@see ResourceObjectFactory)
 * or resource identifier (@see ResourceIdentifierFactory).
 */
abstract class CollectionFactory extends BaseFactory implements CollectionContract
{
    /**
     * A collection of models.
     *
     * @var Collection|null
     */
    protected $collection;

    public function getIlluminateCollection()
    {
        return $this->collection;
    }

    /**
     * Set the collection.
     *
     * @param Collection|array|null $provided     An array of objects implementing ResourceObjectContract
     *                                            or ResourceIdentifierContract
     * @param string|null           $resourceType
     *
     * @return static
     * @throws JsonApiFakerException
     */
    public function setCollection($provided, $resourceType = null)
    {
        $collection = null;
        $array = null;

        if (is_a($provided, Collection::class)) {
            if ($resourceType === null) {
                throw new JsonApiFakerException(Messages::ERROR_TYPE_NOT_NULL);
            }

            $collection = $provided;
            $array = $this->transform($collection, $resourceType);
        }

        if (is_array($provided)) {
            $array = $provided;
            $collection = collect($provided)->map(
                /**
                 * @param ResourceObjectContract|ResourceIdentifierContract $item
                 *
                 * @return \Illuminate\Database\Eloquent\Model
                 * @throws JsonApiFakerException
                 */
                function ($item) {
                    $isValid = is_a($item, ResourceObjectContract::class)
                        || is_a($item, ResourceIdentifierContract::class);
                    if (!$isValid) {
                        throw new JsonApiFakerException(Messages::ERROR_NOT_FACTORY_INSTANCE);
                    }
                    if ($item->getModel() == null) {
                        throw new JsonApiFakerException(Messages::ERROR_MODEL_NOT_SET);
                    }

                    return $item->getModel();
                }
            );
        }

        $this->collection = $collection;
        parent::setCollection($array);

        return $this;
    }

    /**
     * Returns a collection of resource identifier or resource object factories.
     *
     * @param Collection $collection
     * @param string     $resourceType
     *
     * @return array<ResourceObjectContract>|array<ResourceIdentifierContract>
     */
    abstract protected function transform($collection, $resourceType): array;
}
