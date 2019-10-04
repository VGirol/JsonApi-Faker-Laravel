<?php

namespace VGirol\JsonApiFaker\Laravel\Factory;

use Illuminate\Database\Eloquent\Model;
use VGirol\JsonApiFaker\Exception\JsonApiFakerException;

/**
 * An abstract factory for resources (resource object or resource identifer).
 */
trait IsResource
{
    use HasModel;

    /**
     * Class constructor.
     *
     * @param Model|null  $model
     * @param string|null $resourceType
     *
     * @return void
     * @throws JsonApiFakerException
     */
    public function __construct($model = null, ?string $resourceType = null)
    {
        if (($model !== null) && ($resourceType !== null)) {
            $this->setValues($model, $resourceType);
        }
    }

    /**
     * Set the model and the resource type.
     *
     * @param Model  $model
     * @param string $resourceType
     *
     * @return static
     * @throws JsonApiFakerException
     */
    public function setValues($model, string $resourceType)
    {
        return $this->setModel($model)
            ->setId($model->getKey())
            ->setResourceType($resourceType);
    }
}
