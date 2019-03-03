<?php

namespace VGirol\JsonApiAssert\Laravel;

use VGirol\JsonApiAssert\Laravel\Asserts\AssertErrors;
use VGirol\JsonApiAssert\Laravel\Asserts\AssertCreated;
use VGirol\JsonApiAssert\Laravel\Asserts\AssertDeleted;
use VGirol\JsonApiAssert\Laravel\Asserts\AssertNoContent;
use VGirol\JsonApiAssert\Laravel\Asserts\AssertStructure;
use VGirol\JsonApiAssert\Laravel\Asserts\AssertUpdated;
use VGirol\JsonApiAssert\Laravel\Asserts\AssertFetched;
use VGirol\JsonApiAssert\Laravel\Asserts\AssertFetchedCollection;

class AssertResponse
{
    protected static $headerName = 'Content-Type';
    protected static $mediaType = 'application/vnd.api+json';

    use AssertErrors;
    use AssertStructure;
    use AssertNoContent;
    use AssertCreated;
    use AssertDeleted;
    use AssertUpdated;
    use AssertFetched;
    use AssertFetchedCollection;
}
