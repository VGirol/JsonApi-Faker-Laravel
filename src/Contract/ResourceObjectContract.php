<?php

declare(strict_types=1);

namespace VGirol\JsonApiFaker\Laravel\Contract;

use VGirol\JsonApiFaker\Contract\ResourceObjectContract as BaseContract;

/**
 * Interface for resource identifier factory.
 */
interface ResourceObjectContract extends BaseContract, IsResourceContract
{
    /**
     * Add relationship factories.
     *
     * @param array $relationships
     *
     * @return static
     */
    public function appendRelationships(array $relationships);

    /**
     * Add a relationship factory.
     *
     * @param string $name
     * @param string $resourceType
     *
     * @return static
     */
    public function loadRelationship(string $name, string $resourceType);
}
