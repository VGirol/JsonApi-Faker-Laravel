<?php

declare(strict_types=1);

namespace VGirol\JsonApiFaker\Laravel\Contract;

use Illuminate\Support\Collection;
use VGirol\JsonApiFaker\Contract\GeneratorContract as BaseContract;

/**
 * This class is an helper to generate factories.
 */
interface GeneratorContract extends BaseContract
{
    /**
     * Create a resource identifier collection factory.
     *
     * @param Collection  $collection
     * @param string|null $resourceType
     *
     * @return RiCollectionContract
     */
    public function riCollection($collection, ?string $resourceType);

    /**
     * Create a resource object collection factory.
     *
     * @param Collection  $collection
     * @param string|null $resourceType
     *
     * @return RoCollectionContract
     */
    public function roCollection($collection, ?string $resourceType);
}
