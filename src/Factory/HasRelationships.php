<?php
declare (strict_types = 1);

namespace VGirol\JsonApiAssert\Laravel\Factory;

trait HasRelationships
{
    public function appendRelationship(string $name): self
    {
        $collection =$this->getRelatedCollection($name);

        $factory = $this->getCollectionfactory($collection, true);
        $relationship = [
            'data' => $factory->toArray()
        ];
        $this->addRelationship($name, $relationship);

        return $this;
    }

    private function getRelatedCollection(string $name)
    {
        if (!$this->model->relationLoaded($name)) {
            $this->model->load($name);
        }

        $collection = $this->model->getRelation($name);
        $collection = is_null($collection) ? collect([]) : $collection;

        return $collection;
    }

    private function getCollectionFactory($collection, $isRI)
    {
        $className = $this->helper()->getClassName('collection');

        return call_user_func_array([$className, 'create'], [$collection, $isRI]);
    }
}
