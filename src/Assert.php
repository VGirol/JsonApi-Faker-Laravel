<?php

namespace VGirol\JsonApiAssert\Laravel;

use VGirol\JsonApiAssert\Assert as JsonApiAssert;
use VGirol\JsonApiAssert\Laravel\Asserts\Content\AssertJsonapiObject;
use VGirol\JsonApiAssert\Laravel\Asserts\Content\AssertLinks;
use VGirol\JsonApiAssert\Laravel\Asserts\Content\AssertPagination;
use VGirol\JsonApiAssert\Laravel\Asserts\Content\AssertResource;
use VGirol\JsonApiAssert\Laravel\Asserts\Content\AssertResourceLinkage;
use VGirol\JsonApiAssert\Laravel\Asserts\Response\AssertCreated;
use VGirol\JsonApiAssert\Laravel\Asserts\Response\AssertDeleted;
use VGirol\JsonApiAssert\Laravel\Asserts\Response\AssertErrorResponse;
use VGirol\JsonApiAssert\Laravel\Asserts\Response\AssertFetched;
use VGirol\JsonApiAssert\Laravel\Asserts\Response\AssertFetchedCollection;
use VGirol\JsonApiAssert\Laravel\Asserts\Response\AssertFetchedRelationships;
use VGirol\JsonApiAssert\Laravel\Asserts\Response\AssertNoContent;
use VGirol\JsonApiAssert\Laravel\Asserts\Response\AssertUpdated;

/**
 * This class provide a set of assertions to test API response using the JSON:API specification.
 */
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

    use AssertPagination;
    use AssertLinks;
    use AssertJsonapiObject;
    use AssertResource;
    use AssertResourceLinkage;
}
