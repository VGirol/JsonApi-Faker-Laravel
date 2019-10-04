<?php

namespace VGirol\JsonApiFaker\Laravel\Factory;

use Illuminate\Database\Eloquent\Relations\Relation;
use VGirol\JsonApiFaker\Contract\RelationshipContract;
use VGirol\JsonApiFaker\Exception\JsonApiFakerException;
use VGirol\JsonApiFaker\Factory\ResourceObjectFactory as BaseFactory;
use VGirol\JsonApiFaker\Laravel\Contract\ResourceObjectContract;
use VGirol\JsonApiFaker\Laravel\Messages;

/**
 * A factory for resource object.
 */
class ResourceObjectFactory extends BaseFactory implements ResourceObjectContract
{
    use IsResource {
        setValues as setValuesTrait;
    }

    /**
     * @throws JsonApiFakerException
     */
    public function setValues($model, string $resourceType)
    {
        $this->setValuesTrait($model, $resourceType);

        if ($model != null) {
            $this->setAttributes($model->attributesToArray());
        }

        return $this;
    }

    /**
     * Add relationship factories.
     *
     * @param array $relationships
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
     * @throws JsonApiFakerException
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
     * @throws JsonApiFakerException
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
     * @return RelationshipContract
     */
    protected function createRelationshipFactory(...$args)
    {
        return $this->generator
            ->relationship(...$args);
    }

    /**
     * Return the object's relationship.
     *
     * @param string $name
     *
     * @return Relation
     * @throws JsonApiFakerException
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
