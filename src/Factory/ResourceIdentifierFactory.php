<?php

namespace VGirol\JsonApiAssert\Laravel\Factory;

use VGirol\JsonApiAssert\Factory\ResourceIdentifierFactory as BaseFactory;

class ResourceIdentifierFactory extends BaseFactory
{
    use HasModel;

    public function __construct($model, string $resourceType)
    {
        $this->setModel($model)
            ->setId($model->getKey())
            ->setResourceType($resourceType);
    }
}
