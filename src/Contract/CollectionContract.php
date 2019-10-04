<?php

declare(strict_types=1);

namespace VGirol\JsonApiFaker\Laravel\Contract;

use Illuminate\Support\Collection;
use VGirol\JsonApiFaker\Contract\CollectionContract as BaseContract;

/**
 * Interface for resource identifier factory.
 */
interface CollectionContract extends BaseContract
{
    /**
     * Get the \Illuminate\Support\Collection.
     *
     * @return Collection|null
     */
    public function getIlluminateCollection();
}
