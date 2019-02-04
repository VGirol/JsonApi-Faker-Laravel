<?php
namespace VGirol\JsonApi\Tests\Unit\Check;

use VGirol\JsonApi\Tests\TestCase;
use VGirol\JsonApi\Tools\ClassNameTools;
use VGirol\JsonApi\Tools\Assert\JsonApiAssertBase;
use VGirol\JsonApi\Tools\Assert\JsonApiAssertObject;

class JsonApiObjectsTest extends TestCase
{
    use ClassNameTools;
    use JsonApiAssertBase, JsonApiAssertObject;

    use JsonApiMemberNameTest;
    use JsonApiResourceObjectTest;
    use JsonApiMetaObjectTest;
    use JsonApiAttributesObjectTest;
    use JsonApiLinksObjectTest;
    use JsonApiErrorsObjectTest;
    use JsonApiJsonapiObjectTest;
    use JsonApiRelationshipsObjectTest;
}
