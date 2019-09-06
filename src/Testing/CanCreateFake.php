<?php

namespace VGirol\JsonApiFaker\Laravel\Testing;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use VGirol\JsonApiAssert\Members;

trait CanCreateFake
{
    protected $modelClass = DummyModel::class;

    /**
     * Undocumented function
     *
     * @return DummyModel
     */
    protected function createModel(): DummyModel
    {
        return ($this->modelClass)::fake();
    }

    /**
     * Undocumented function
     *
     * @param integer $count
     *
     * @return Collection
     */
    protected function createCollection($count = 5): Collection
    {
        $collection = new Collection();
        for ($i = 1; $i <= $count; $i++) {
            $collection->push($this->createModel());
        }

        return $collection;
    }

    /**
     * Undocumented function
     *
     * @param Model $model
     * @param string $resourceType
     * @param boolean $isResourceIdentifier
     * @param string|null $withError
     * @param array|null $additional
     *
     * @return array
     */
    protected function createResource(
        $model,
        string $resourceType,
        bool $isResourceIdentifier,
        $withError = null,
        $additional = null
    ): array {
        $resource = [
            Members::TYPE => $resourceType,
            Members::ID => strval($model->getKey())
        ];
        if (!$isResourceIdentifier) {
            $resource[Members::ATTRIBUTES] = $model->attributesToArray();
        }
        if ($withError !== null) {
            switch ($withError) {
                case 'value':
                    $error = 10;
                    $resource[Members::ID] = strval($model->getKey() + $error);
                    break;
                case 'structure':
                    $resource[Members::ID] = intval($model->getKey());
                    break;
            }
        }
        if ($additional !== null) {
            $resource = array_merge($resource, $additional);
        }

        return $resource;
    }

    /**
     * Undocumented function
     *
     * @param Collection $collection
     * @param string $resourceType
     * @param bool $isResourceIdentifier
     * @param string|null $withError
     *
     * @return array
     */
    protected function createResourceCollection(
        Collection $collection,
        string $resourceType,
        bool $isResourceIdentifier,
        $withError = null
    ): array {
        $data = [];
        $index = rand(1, $collection->count() - 1);
        foreach ($collection as $i => $model) {
            array_push(
                $data,
                $this->createResource(
                    $model,
                    $resourceType,
                    $isResourceIdentifier,
                    (($withError !== null) && ($i == $index)) ? $withError : null
                )
            );
        }

        return $data;
    }
}
