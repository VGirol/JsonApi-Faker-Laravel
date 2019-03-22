<?php

namespace VGirol\JsonApiAssert\Laravel;

trait HeaderTrait
{
    protected static $headerName = 'Content-Type';
    protected static $mediaType = 'application/vnd.api+json';
}
