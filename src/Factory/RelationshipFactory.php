<?php

namespace VGirol\JsonApiAssert\Laravel\Factory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use VGirol\JsonApiAssert\Factory\RelationshipFactory as BaseFactory;

class RelationshipFactory extends BaseFactory
{
    /**
     * Undocumented variable
     *
     * @var string
     */
    protected $name;

    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * Undocumented function
     *
     * @param ResourceIdentifierFactory|CollectionFactory|Model|Collection|null $data
     * @param string|null $resourceType
     * @return static
     */
    public function setData($data, $resourceType = null)
    {
        if (is_a($data, Model::class)) {
            $data = HelperFactory::create('resource-identifier', $data, $resourceType);
        }
        if (is_a($data, Collection::class)) {
            $data = HelperFactory::create('collection', $data, $resourceType, true);
        }
        $this->data = $data;

        return $this;
    }
}
