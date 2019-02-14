<?php
namespace VGirol\JsonApi\Tools\Assert;

use VGirol\JsonApi\Tools\Assert\JsonApiAssertBase;
use VGirol\JsonApi\Tools\Assert\JsonApiAssertObject;
use VGirol\JsonApi\Tools\Assert\JsonApiAssertResource;
use VGirol\JsonApi\Tools\Assert\JsonApiAssertStructure;

trait JsonApiAssert
{
    use JsonApiAssertBase;
    use JsonApiAssertObject;
    use JsonApiAssertResource;
    use JsonApiAssertStructure;
}
