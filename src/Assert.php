<?php
namespace VGirol\JsonApiAssert;

use VGirol\JsonApiAssert\Asserts\AssertBase;
use VGirol\JsonApiAssert\Asserts\AssertStructure;
use VGirol\JsonApiAssert\Asserts\AssertMemberName;
use VGirol\JsonApiAssert\Asserts\AssertMetaObject;
use VGirol\JsonApiAssert\Asserts\AssertLinksObject;
use VGirol\JsonApiAssert\Asserts\AssertErrorsObject;
use VGirol\JsonApiAssert\Asserts\AssertJsonapiObject;
use VGirol\JsonApiAssert\Asserts\AssertResourceObject;
use VGirol\JsonApiAssert\Asserts\AssertAttributesObject;
use VGirol\JsonApiAssert\Asserts\AssertRelationshipsObject;

class Assert
{
    use AssertAttributesObject;
    use AssertBase;
    use AssertErrorsObject;
    use AssertJsonapiObject;
    use AssertLinksObject;
    use AssertMemberName;
    use AssertMetaObject;
    use AssertRelationshipsObject;
    use AssertResourceObject;
    use AssertStructure;
}
