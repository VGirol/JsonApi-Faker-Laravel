<?php

namespace VGirol\JsonApiAssert\Laravel\Factory;

use VGirol\JsonApiAssert\Factory\ResourceObjectFactory as BaseFactory;

class ResourceObjectFactory extends BaseFactory
{
    use HasModel;
    use HasRelationships;

    public function __construct($model, ?string $resourceType)
    {
        $this->setModel($model)
            ->setId($model->getKey())
            ->setResourceType($resourceType)
            ->setAttributes($model->attributesToArray());
    }
}
