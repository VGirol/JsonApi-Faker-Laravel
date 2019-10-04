<?php

declare(strict_types=1);

namespace VGirol\JsonApiFaker\Laravel\Contract;

use Illuminate\Database\Eloquent\Model;

/**
 * Interface for classes having model property.
 */
interface HasModelContract
{
    /**
     * Set the model.
     *
     * @param Model $model
     *
     * @return static
     */
    public function setModel($model);

    /**
     * Get the model.
     *
     * @return Model
     */
    public function getModel();

    /**
     * Get the value of the model's primary key.
     *
     * @return mixed
     */
    public function getKey();
}
