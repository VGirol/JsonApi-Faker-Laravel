<?php

namespace VGirol\JsonApiAssert\Laravel\Factory;

use VGirol\JsonApiAssert\Factory\HelperFactory as BaseFactory;

class HelperFactory extends BaseFactory
{
    public static function getAliases(): array
    {
        return array_merge(
            parent::getAliases(),
            [
                'collection' => CollectionFactory::class,
                'relationship' => RelationshipFactory::class,
                'resource-identifier' => ResourceIdentifierFactory::class,
                'resource-object' => ResourceObjectFactory::class
            ]
        );
    }
}
