<?php

namespace VGirol\JsonApiFaker\Laravel\Factory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use VGirol\JsonApiFaker\Factory\RelationshipFactory as BaseFactory;
use VGirol\JsonApiFaker\Laravel\Generator;

class RelationshipFactory extends BaseFactory
{
    /**
     * Undocumented variable
     *
     * @var string
     */
    public $name;

    /**
     * Undocumented function
     *
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * Undocumented function
     *
     * @param ResourceIdentifierFactory|CollectionFactory|Model|Collection|null $data
     * @param string $resourceType
     * @param string $routeName
     * @return static
     */
    public function setData($data, string $resourceType = null, string $routeName = null)
    {
        if (is_a($data, Model::class)) {
            $data = Generator::getInstance()->resourceIdentifier($data, $resourceType);
        }
        if (is_a($data, Collection::class)) {
            $data = Generator::getInstance()->collection($data, $resourceType, $routeName, true);
        }
        $this->data = $data;

        return $this;
    }
}
