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
}
