<?php

namespace VGirol\JsonApiAssert\Laravel;

use VGirol\JsonApiAssert\Assert as JsonApiAssert;
use VGirol\JsonApiAssert\Laravel\Asserts\Response\AssertCreated;
use VGirol\JsonApiAssert\Laravel\Asserts\Response\AssertDeleted;
use VGirol\JsonApiAssert\Laravel\Asserts\Response\AssertErrorResponse;
use VGirol\JsonApiAssert\Laravel\Asserts\Response\AssertFetched;
use VGirol\JsonApiAssert\Laravel\Asserts\Response\AssertFetchedCollection;
use VGirol\JsonApiAssert\Laravel\Asserts\Response\AssertFetchedRelationships;
use VGirol\JsonApiAssert\Laravel\Asserts\Response\AssertNoContent;
use VGirol\JsonApiAssert\Laravel\Asserts\Response\AssertUpdated;
use VGirol\JsonApiAssert\Laravel\Asserts\Structure\AssertErrors;
use VGirol\JsonApiAssert\Laravel\Asserts\Structure\AssertResource;
use VGirol\JsonApiAssert\Laravel\Asserts\Structure\AssertResourceLinkage;

class Assert extends JsonApiAssert
{
    use HeaderTrait;

    use AssertErrorResponse;
    // use AssertHelpers;
    use AssertNoContent;
    use AssertCreated;
    use AssertDeleted;
    use AssertUpdated;
    use AssertFetched;
    use AssertFetchedCollection;
    use AssertFetchedRelationships;

    use AssertResource;
    use AssertResourceLinkage;
    use AssertErrors;
}
