<?php

namespace VGirol\JsonApiFaker\Laravel\Factory;

use Illuminate\Database\Eloquent\Model;
use VGirol\JsonApiFaker\Factory\ResourceIdentifierFactory as BaseFactory;

/**
 * A factory for resource identifier object
 */
class ResourceIdentifierFactory extends BaseFactory
{
    use HasModel;

    /**
     * Class constructor
     *
     * @param Model|null $model
     * @param string|null $resourceType
     */
    public function __construct($model = null, ?string $resourceType = null)
    {
        $this->setModel($model)
            ->setId(($model === null) ? null : $model->getKey())
            ->setResourceType($resourceType);
    }
}
