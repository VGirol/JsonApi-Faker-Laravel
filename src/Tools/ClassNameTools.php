<?php

namespace VGirol\JsonApi\Tools;

trait ClassNameTools
{
    protected function modelNamespace(): string
    {
        return '\\App\\Models\\';
    }

    protected function resourceNamespace(): string
    {
        return '\\App\\Http\\Resources\\';
    }

    public function getModelTable(string $className = NULL): string
    {
        if (is_null($className)) {
            $className = $this->getModelClassName();
        }

        return (new $className())->getTable();
    }

    public function getModelKeyName(string $className = NULL): string
    {
        if (is_null($className)) {
            $className = $this->getModelClassName();
        }

        return (new $className())->getKeyName();
    }

    public function getObjectResourceType(string $className = NULL) : string
    {
        if (is_null($className)) {
            $className = $this->getModelClassName();
        }

        return (new $className())->getResourceType();
    }

    public function getModelClassName(string $className = NULL): string
    {
        return $this->modelNamespace().$this->getRootName($className);
    }

    public function getResourceClassName(string $className = NULL): string
    {
        return $this->resourceNamespace().$this->getRootName($className).'Resource';
    }

    public function getResourceCollectionClassName($related = NULL): string
    {
        if (!is_null($related)) {
            return $this->getRelationshipsResourceCollectionClassName($related);
        }

        return $this->getResourceClassName().'Collection';
    }

    public function getRelationshipsResourceCollectionClassName($related): string
    {
        return $this->resourceNamespace().ucfirst(substr($related, 0, -1)).'ResourceCollection';
    }

    private function getRootName($class = NULL): string
    {
        if (is_null($class)) {
            $class = get_called_class();
        }
        $a = explode('\\', $class);
        $rootName = array_pop($a);
        $rootName = str_replace(
            ['Controller', 'FormRequest', 'Resource', 'ResourceCollection'],
            NULL,
            $rootName);

        return $rootName;
    }

}
