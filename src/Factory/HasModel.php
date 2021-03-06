<?php

declare(strict_types=1);

namespace VGirol\JsonApiFaker\Laravel\Factory;

use Illuminate\Database\Eloquent\Model;
use VGirol\JsonApiFaker\Exception\JsonApiFakerException;
use VGirol\JsonApiFaker\Laravel\Messages;

/**
 * Add "model" member to a factory.
 */
trait HasModel
{
    /**
     * The model instance.
     *
     * @var Model
     */
    protected $model;

    /**
     * Set the model.
     *
     * @param Model $model
     *
     * @return static
     * @throws JsonApiFakerException
     */
    public function setModel($model)
    {
        if ($model == null) {
            throw new JsonApiFakerException(Messages::ERROR_MODEL_NOT_NULL);
        }
        if (is_a($model, Model::class) === false) {
            throw new JsonApiFakerException(Messages::ERROR_MODEL_NOT_OBJECT);
        }

        $this->model = $model;

        return $this;
    }

    /**
     * Get the model.
     *
     * @return Model
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Get the value of the model's primary key.
     *
     * @return mixed
     * @throws JsonApiFakerException
     */
    public function getKey()
    {
        if ($this->model == null) {
            throw new JsonApiFakerException(Messages::ERROR_MODEL_NOT_SET);
        }

        return $this->model->getKey();
    }
}
