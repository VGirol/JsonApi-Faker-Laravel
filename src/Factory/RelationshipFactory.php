<?php

namespace VGirol\JsonApiFaker\Laravel\Factory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use VGirol\JsonApiFaker\Factory\RelationshipFactory as BaseFactory;
use VGirol\JsonApiFaker\Laravel\Generator;
use VGirol\JsonApiFaker\Laravel\Messages;

class RelationshipFactory extends BaseFactory
{
    /**
     * @inheritDoc
     *
     * @param ResourceIdentifierFactory|CollectionFactory|Model|Collection|null $data
     * @param string $resourceType
     *
     * @return static
     */
    public function setData($data, string $resourceType = null)
    {
        if ($data !== null) {
            if ($resourceType === null) {
                throw new \Exception(Messages::ERROR_TYPE_NOT_NULL);
            }

            if (is_a($data, Model::class)) {
                $data = Generator::getInstance()->resourceIdentifier($data, $resourceType);
            }
            if (is_a($data, Collection::class)) {
                $data = Generator::getInstance()->riCollection($data, $resourceType);
            }
        }

        return parent::setData($data);
    }
}
