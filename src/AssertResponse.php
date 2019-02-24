<?php

namespace VGirol\JsonApiAssert\Laravel;

use VGirol\JsonApiAssert\Laravel\Asserts\AssertBadRequest;
use VGirol\JsonApiAssert\Laravel\Asserts\AssertDeletion;

class AssertResponse
{
    protected static $headerName = 'Content-Type';
    protected static $mediaType = 'application/vnd.api+json';

    use AssertBadRequest;
    use AssertDeletion;
}
