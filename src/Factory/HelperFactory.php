<?php

namespace VGirol\JsonApiAssert\Laravel\Factory;

use Illuminate\Database\Eloquent\Model;
use VGirol\JsonApiAssert\Factory\HelperFactory as BaseFactory;
use VGirol\JsonApiAssert\Laravel\Factory\ResourceIdentifierFactory;

class HelperFactory extends BaseFactory
{
    public static function getAliases(): array
    {
        return array_merge(parent::getAliases(), [
            'collection' => CollectionFactory::class,
            'relationship' => RelationshipFactory::class,
            'resource-identifier' => ResourceIdentifierFactory::class,
            'resource-object' => ResourceObjectFactory::class
        ]);
    }

    /**
     * Undocumented function
     *
     * @param Model $model
     * @param string $resourceType
     * @param string $routeName
     * @return ResourceObject
     */
    public static function createResourceObject($model, string $resourceType, string $routeName)
    {
        return static::create('resource-object', $model, $resourceType, $routeName);
    }

    public static function createResourceIdentifier($model, ?string $resourceType)
    {
        return static::create('resource-identifier', $model, $resourceType);
    }

    public static function createCollection($collection, ?string $resourceType, ?string $routeName, $isRI = false)
    {
        return static::create('collection', $collection, $resourceType, $routeName, $isRI);
    }

    public static function createRelationship($name)
    {
        return static::create('relationship', $name);
    }
}
