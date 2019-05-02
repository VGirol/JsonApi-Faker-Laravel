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
use VGirol\JsonApiAssert\Laravel\Asserts\Structure\AssertJsonapiObject;
use VGirol\JsonApiAssert\Laravel\Asserts\Structure\AssertLinks;
use VGirol\JsonApiAssert\Laravel\Asserts\Structure\AssertPagination;

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

    use AssertErrors;
    use AssertPagination;
    use AssertLinks;
    use AssertJsonapiObject;
}
