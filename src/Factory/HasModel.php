<?php
declare (strict_types = 1);

namespace VGirol\JsonApiAssert\Laravel\Factory;

trait HasModel
{
    protected $model;

    public function setModel($model): self
    {
        $this->model = $model;

        return $this;
    }

    public function appendRelationship(string $name): self
    {
        if (!$this->model->relationLoaded($name)) {
            $this->model->load($name);
        }

        $collection = $this->model->getRelation($name);
        $factory = $this->collectionfactory($collection, true);
        $relationship = [
            'data' => $factory->toArray()
        ];
        $this->addRelationship($name, $relationship);

        return $this;
    }

    protected function collectionFactory($collection, $isRI)
    {
        return $this->factory(CollectionFactory::class, [$collection, $isRI]);
    }
}
