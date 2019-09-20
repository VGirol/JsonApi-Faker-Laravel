<?php

namespace VGirol\JsonApiFaker\Laravel\Factory;

use Illuminate\Database\Eloquent\Relations\Relation;
use VGirol\JsonApiFaker\Exception\JsonApiFakerException;
use VGirol\JsonApiFaker\Factory\ResourceObjectFactory as BaseFactory;
use VGirol\JsonApiFaker\Laravel\Messages;

/**
 * A factory for resource object.
 */
class ResourceObjectFactory extends BaseFactory
{
    use IsResource {
        setValues as setValuesTrait;
    }

    /**
     * {@inheritdoc}
     */
    public function setValues($model, string $resourceType)
    {
        $this->setValuesTrait($model, $resourceType);

        if ($model != null) {
            $this->setAttributes($model->attributesToArray());
        }
    }

    /**
     * Add relationship factories.
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
     * Add a relationship factory.
     *
     * @param string $name
     * @param string $resourceType
     *
     * @return static
     */
    public function loadRelationship(string $name, string $resourceType)
    {
        $relationship = $this->createRelationshipFactory();

        return $this->fillRelationship($relationship, $name, $resourceType)
            ->addRelationship($name, $relationship);
    }

    /**
     * Fill a relationship factory.
     *
     * Creates a \Illuminate\Database\Eloquent\Relations\Relation instance
     * and fill the relationshipFactory with it.
     *
     * @param RelationshipFactory $relationship
     * @param string              $name
     * @param string              $resourceType
     *
     * @return static
     */
    protected function fillRelationship($relationship, string $name, string $resourceType)
    {
        $relation = $this->getRelationObject($name);
        $relationship->setData($relation, $resourceType);

        return $this;
    }

    /**
     * Create an instance of RelationshipFactory.
     *
     * @param mixed ...$args
     *
     * @return RelationshipFactory
     */
    protected function createRelationshipFactory(...$args)
    {
        return $this->generator
            ->relationship(...$args)
            ->setGenerator($this->generator);
    }

    /**
     * Return the object's relationship.
     *
     * @param string $name
     *
     * @throws JsonApiFakerException
     *
     * @return Relation
     */
    private function getRelationObject(string $name)
    {
        if ($this->model === null) {
            throw new JsonApiFakerException(Messages::ERROR_MODEL_NOT_SET);
        }

        if (!$this->model->relationLoaded($name)) {
            $this->model->load($name);
        }

        return $this->model->getRelation($name);
    }
}
