<?php

namespace VGirol\JsonApiFaker\Laravel\Factory;

use VGirol\JsonApiFaker\Factory\ResourceIdentifierFactory as BaseFactory;
use VGirol\JsonApiFaker\Laravel\Contract\ResourceIdentifierContract;

/**
 * A factory for resource identifier object.
 */
class ResourceIdentifierFactory extends BaseFactory implements ResourceIdentifierContract
{
    use IsResource;
}
