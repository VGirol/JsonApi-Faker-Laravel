<?php
namespace VGirol\JsonApi\Exceptions;

use Exception;

class JsonApiException extends Exception {

    public function getStatusCode()
    {
        return 400;
    }
}
