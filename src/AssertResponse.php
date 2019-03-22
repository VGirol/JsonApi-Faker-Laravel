<?php

namespace VGirol\JsonApiAssert\Laravel;

use VGirol\JsonApiAssert\Laravel\Asserts\AssertErrors;
use VGirol\JsonApiAssert\Laravel\Asserts\AssertCreated;
use VGirol\JsonApiAssert\Laravel\Asserts\AssertDeleted;
use VGirol\JsonApiAssert\Laravel\Asserts\AssertFetched;
use VGirol\JsonApiAssert\Laravel\Asserts\AssertUpdated;
use VGirol\JsonApiAssert\Laravel\Asserts\AssertResource;
use VGirol\JsonApiAssert\Laravel\Asserts\AssertNoContent;
use VGirol\JsonApiAssert\Laravel\Asserts\AssertFetchedCollection;
use VGirol\JsonApiAssert\Laravel\Asserts\AssertFetchedRelationships;
use VGirol\JsonApiAssert\Laravel\Asserts\AssertHelpers;
use VGirol\JsonApiAssert\Laravel\Asserts\AssertResourceLinkage;

class AssertResponse
{
    use HeaderTrait;

    use AssertErrors;
    use AssertHelpers;
    use AssertResourceLinkage;
    use AssertResource;
    use AssertNoContent;
    use AssertCreated;
    use AssertDeleted;
    use AssertUpdated;
    use AssertFetched;
    use AssertFetchedCollection;
    use AssertFetchedRelationships;
}
