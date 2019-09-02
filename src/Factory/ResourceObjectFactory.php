<?php

namespace VGirol\JsonApiFaker\Laravel\Factory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use VGirol\JsonApiFaker\Factory\ResourceObjectFactory as BaseFactory;
use VGirol\JsonApiFaker\Laravel\Generator;
use VGirol\JsonApiFaker\Laravel\Messages;

/**
 * A factory for resource object
 */
class ResourceObjectFactory extends BaseFactory
{
    use HasModel;

    /**
     * Class constructor
     *
     * @param Model|null $model
     * @param string|null $resourceType
     */
    public function __construct($model, ?string $resourceType)
    {
        $this->setModel($model)
            ->setResourceType($resourceType);

        if ($model !== null) {
            $this->setId($model->getKey())
                ->setAttributes($model->attributesToArray());
        }
    }

    /**
     * Add relationship factories
     *
     * @param array<string,string> $relationships
     *
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
     * Add a relationship factory
     *
     * @param string $name
     * @param string $resourceType
     *
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
     * Fill a relationship factory with a \Illuminate\Database\Eloquent\Relations\Relation instance
     *
     * @param RelationshipFactory $relationship
     * @param Relation $relation
     * @param string $resourceType
     *
     * @return void
     */
    protected function fillRelationship($relationship, $relation, string $resourceType): void
    {
        $relationship->setData($relation, $resourceType);
    }

    /**
     * Create an instance of RelationshipFactory
     *
     * @param mixed ...$args
     *
     * @return RelationshipFactory
     */
    protected function createRelationshipFactory(...$args)
    {
        return Generator::getInstance()->relationship(...$args);
    }

    /**
     * Return the object's relationship
     *
     * @param string $name
     *
     * @return Relation
     * @throws \Exception
     */
    private function getRelationObject(string $name)
    {
        if ($this->model === null) {
            throw new \Exception(Messages::ERROR_NO_MODEL);
        }

        if (!$this->model->relationLoaded($name)) {
            $this->model->load($name);
        }

        return $this->model->getRelation($name);
    }
}
