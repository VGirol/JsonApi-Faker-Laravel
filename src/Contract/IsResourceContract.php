<?php

declare(strict_types=1);

namespace VGirol\JsonApiFaker\Laravel\Contract;

use Illuminate\Database\Eloquent\Model;

/**
 * Interface for classes having model property.
 */
interface IsResourceContract extends HasModelContract
{
    /**
     * Set the model and the resource type.
     *
     * @param Model  $model
     * @param string $resourceType
     *
     * @return static
     */
    public function setValues($model, string $resourceType);
}
