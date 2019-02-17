<?php
namespace VGirol\JsonApiAssert;

use VGirol\JsonApiAssert\JsonApiAssertBase;
use VGirol\JsonApiAssert\JsonApiAssertCrud;
use VGirol\JsonApiAssert\JsonApiAssertStructure;
use VGirol\JsonApiAssert\JsonApiAssertMemberName;
use VGirol\JsonApiAssert\JsonApiAssertMetaObject;
use VGirol\JsonApiAssert\JsonApiAssertLinksObject;
use VGirol\JsonApiAssert\JsonApiAssertErrorsObject;
use VGirol\JsonApiAssert\JsonApiAssertJsonapiObject;
use VGirol\JsonApiAssert\JsonApiAssertResourceObject;
use VGirol\JsonApiAssert\JsonApiAssertAttributesObject;
use VGirol\JsonApiAssert\JsonApiAssertRelationshipsObject;

trait JsonApiAssert
{
    use JsonApiAssertAttributesObject;
    use JsonApiAssertBase;
    use JsonApiAssertErrorsObject;
    use JsonApiAssertJsonapiObject;
    use JsonApiAssertLinksObject;
    use JsonApiAssertMemberName;
    use JsonApiAssertMetaObject;
    use JsonApiAssertRelationshipsObject;
    use JsonApiAssertResourceObject;
    use JsonApiAssertStructure;

    use JsonApiAssertCrud;
}
