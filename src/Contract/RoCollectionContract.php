<?php

declare(strict_types=1);

namespace VGirol\JsonApiFaker\Laravel\Contract;

/**
 * Interface for resource object collection factory.
 */
interface RoCollectionContract extends CollectionContract
{
    /**
     * Add a relationship to the resource object.
     *
     * @param array $relationships
     *
     * @return static
     */
    public function appendRelationships(array $relationships);
}
