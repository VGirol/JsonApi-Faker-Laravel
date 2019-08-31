<?php

declare(strict_types=1);

namespace VGirol\JsonApiFaker\Laravel\Factory;

use Illuminate\Database\Eloquent\Model;

trait HasModel
{
    /**
     * The model instance
     *
     * @var Model|null
     */
    public $model;

    /**
     * Set the model
     *
     * @param Model|null $model
     *
     * @return static
     */
    public function setModel($model)
    {
        $this->model = $model;

        return $this;
    }

    /**
     * Get the value of the model's primary key.
     *
     * @return mixed
     * @throws \Exception
     */
    public function getKey()
    {
        if ($this->model == null) {
            throw new \Exception('The model is not set.');
        }

        return $this->model->getKey();
    }
}
