<?php

namespace VGirol\JsonApiFaker\Laravel\Factory;

use VGirol\JsonApiFaker\Factory\ResourceIdentifierFactory as BaseFactory;

/**
 * A factory for resource identifier object.
 */
class ResourceIdentifierFactory extends BaseFactory
{
    use IsResource;
}
