<?php

namespace VGirol\JsonApi\Tools;

use VGirol\JsonApi\Resources\JsonApiResource;
use VGirol\JsonApi\Resources\JsonApiResourceCollection;
use VGirol\JsonApi\Exceptions\JsonApiException;

trait ClassNameTools
{
    // protected $dictionary = [
    //     'primary' => [
    //         'model' => null,
    //         'resource' => null,
    //         'resource-collection' => null,
    //     ],
    //     'relatonships' => []
    // ];

    protected abstract function getDictionary();

    protected function getModelClassName($relationship = null) : string
    {
        $path = (is_null($relationship)) ? 'primary.model' : 'relationships.' . $relationship . '.model';

        return $this->getDictionaryValue($path);
    }

    protected function getResourceClassName($relationship = null) : string
    {
        try {
            $path = (is_null($relationship)) ? 'primary.resource' : 'relationships.' . $relationship . '.resource';
            $name = $this->getDictionaryValue($path);
        } catch (JsonApiException $e) {
            $name = JsonApiResource::class;
        }

        return $name;
    }

    protected function getResourceCollectionClassName($relationship = null) : string
    {
        try {
            $path = (is_null($relationship)) ? 'primary.resource-collection' : 'relationships.' . $relationship . '.resource-collection';
            $name = $this->getDictionaryValue($path);
        } catch (JsonApiException $e) {
            $name = JsonApiResourceCollection::class;
        }

        return $name;
    }

    protected function getModelTable(string $className = null) : string
    {
        return $this->getModel($className)->getTable();
    }

    protected function getModelKeyName(string $className = null) : string
    {
        return $this->getModel($className)->getKeyName();
    }

    protected function getObjectResourceType(string $className = null) : string
    {
        return $this->getModel($className)->getResourceType();
    }

    private function getModel(string $className = null)
    {
        if (is_null($className)) {
            $className = $this->getModelClassName();
        }

        return new $className();
    }

    private function getDictionaryValue(string $path) : string
    {
        $keys = explode('.', $path);
        $ret = $this->getDictionary();
        foreach ($keys as $key) {
            if (isset($ret[$key])) {
                $ret = $ret[$key];
            } else {
                throw new JsonApiException();
            }
        }

        return $ret;
    }
}
