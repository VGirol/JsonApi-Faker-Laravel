<?php
declare (strict_types = 1);

namespace VGirol\JsonApiAssert\Laravel\Factory;

use Illuminate\Database\Eloquent\Relations\Relation;

trait HasRelationships
{
    /**
     * Undocumented function
     *
     * @param string $name
     * @param string $resourceType
     * @param string $className
     * @return static
     */
    public function loadRelationship(string $name, string $resourceType)
    {
        $relation = $this->getRelation($name);

        $relationship = $this->createRelationshipFactory($name);
        $this->fillRelationshipFactory($relationship, $relation, $resourceType);
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
    protected function fillRelationshipFactory($relationship, $relation, string $resourceType): void
    {
        $relationship->setData($relation, $resourceType);
    }

    /**
     * Undocumented function
     *
     * @param string $name
     * @return Relation
     */
    protected function getRelation(string $name)
    {
        if (!$this->model->relationLoaded($name)) {
            $this->model->load($name);
        }

        return $this->model->getRelation($name);
    }

    /**
     * Undocumented function
     *
     * @param mixed ...$args
     * @return RelationshipFactory
     */
    protected function createRelationshipFactory(...$args)
    {
        return HelperFactory::create('relationship', ...$args);
    }
}
