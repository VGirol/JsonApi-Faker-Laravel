<?php

namespace VGirol\JsonApiFaker\Laravel\Factory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use VGirol\JsonApiFaker\Factory\RelationshipFactory as BaseFactory;

class RelationshipFactory extends BaseFactory
{
    /**
     * @inheritDoc
     *
     * @param ResourceIdentifierFactory|CollectionFactory|Model|Collection|null $data
     * @param string|null $resourceType
     *
     * @return static
     */
    public function setData($data, string $resourceType = null)
    {
        if ($data !== null) {
            if (is_a($data, Model::class)) {
                $data = $this->generator->resourceIdentifier($data, $resourceType);
            }
            if (is_a($data, Collection::class)) {
                $data = $this->generator->riCollection($data, $resourceType);
            }
        }

        return parent::setData($data);
    }
}
