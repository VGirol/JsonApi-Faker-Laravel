<?php

namespace VGirol\JsonApiFaker\Laravel\Factory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use VGirol\JsonApiFaker\Exception\JsonApiFakerException;
use VGirol\JsonApiFaker\Factory\RelationshipFactory as BaseFactory;
use VGirol\JsonApiFaker\Laravel\Contract\CollectionContract;
use VGirol\JsonApiFaker\Laravel\Contract\GeneratorContract;
use VGirol\JsonApiFaker\Laravel\Contract\ResourceIdentifierContract;

/**
 * A factory for "relationship" object
 */
class RelationshipFactory extends BaseFactory
{
    /**
     * The factory generator
     *
     * @var GeneratorContract
     */
    protected $generator;

    /**
     * @param ResourceIdentifierContract|CollectionContract|Model|Collection|null $data
     * @param string|null                                                         $resourceType
     *
     * @return static
     * @throws JsonApiFakerException
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
