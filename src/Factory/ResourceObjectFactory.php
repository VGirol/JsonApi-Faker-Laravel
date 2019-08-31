<?php

namespace VGirol\JsonApiFaker\Laravel\Factory;

use VGirol\JsonApiFaker\Factory\ResourceObjectFactory as BaseFactory;
use VGirol\JsonApiFaker\Laravel\Generator;

class ResourceObjectFactory extends BaseFactory
{
    use HasModel;
    use HasRouteName;

    public function __construct($model, string $resourceType, string $routeName)
    {
        $this->setModel($model)
            ->setResourceType($resourceType)
            ->setRouteName($routeName)
            ->setId($model->getKey())
            ->setAttributes($model->attributesToArray());
    }

    /**
     * Undocumented function
     *
     * @param array $relationships
     * @return static
     */
    public function appendRelationships(array $relationships)
    {
        foreach ($relationships as $name => $resourceType) {
            $this->loadRelationship($name, $resourceType);
        }

        return $this;
    }

    /**
     * Undocumented function
     *
     * @param string $path
     * @param string $resourceType
     * @return static
     */
    public function loadRelationship(string $name, string $resourceType)
    {
        $relation = $this->getRelationObject($name);

        $relationship = $this->createRelationshipFactory($name);
        $this->fillRelationship($relationship, $relation, $resourceType);
        $this->addRelationship($name, $relationship);

        return $this;
    }

    /**
     * Undocumented function
     *
     * @param RelationshipFactory $relationship
     * @param Relation $relation
     * @param string $resourceType
     * @return void
     */
    protected function fillRelationship($relationship, $relation, string $resourceType): void
    {
        $relationship->setData($relation, $resourceType, $this->routeName);
    }

    /**
     * Undocumented function
     *
     * @param mixed ...$args
     * @return RelationshipFactory
     */
    protected function createRelationshipFactory(...$args)
    {
        return Generator::getInstance()->relationship(...$args);
    }

    /**
     * Undocumented function
     *
     * @param string $name
     * @return Relation
     */
    private function getRelationObject(string $name)
    {
        if (!$this->model->relationLoaded($name)) {
            $this->model->load($name);
        }

        return $this->model->getRelation($name);
    }
}
