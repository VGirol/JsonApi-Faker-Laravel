<?php
namespace VGirol\JsonApi\Tests\Unit\Check;

use VGirol\JsonApi\Tests\TestCase;
use VGirol\JsonApi\Tools\ClassNameTools;
use VGirol\JsonApi\Tools\Assert\JsonApiAssertBase;
use VGirol\JsonApi\Tools\Assert\JsonApiAssertObject;
use VGirol\JsonApi\Tools\Assert\JsonApiAssertStructure;

class JsonApiResponseStructureTest extends TestCase
{
    use ClassNameTools;
    use JsonApiAssertBase, JsonApiAssertObject, JsonApiAssertStructure;

    use JsonApiTopLevelMembersTest;
    use JsonApiPrimaryDataTest;
    use JsonApiIncludedTest;
    use JsonApiStructureTest;
}
