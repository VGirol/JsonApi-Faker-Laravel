<?php
namespace VGirol\JsonApi\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Support\Arrayable;
use VGirol\JsonApi\Exceptions\JsonApiException;
use VGirol\JsonApi\Exceptions\JsonApiValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

trait JsonApiHttpResponseTrait
{

    private $jsonResponse = [];

    /* --------------- HTTP Responses ------------------- */

    protected function ok() : JsonResponse
    {
        return $this->createResponse(JsonResponse::HTTP_OK);
    }

    protected function created() : JsonResponse
    {
        return $this->createResponse(JsonResponse::HTTP_CREATED);
    }

    protected function noContent() : JsonResponse
    {
        $this->jsonResponse = [];

        return $this->createResponse(JsonResponse::HTTP_NO_CONTENT);
    }

    protected function badRequest(string $message) : JsonResponse
    {
        return $this->createErrorResponse(JsonResponse::HTTP_BAD_REQUEST, $message);
    }

    protected function unauthorized(string $message) : JsonResponse
    {
        return $this->createErrorResponse(JsonResponse::HTTP_UNAUTHORIZED, $message);
    }

    protected function notFound(string $message) : JsonResponse
    {
        return $this->createErrorResponse(JsonResponse::HTTP_NOT_FOUND, $message);
    }

    protected function notAcceptable(string $message) : JsonResponse
    {
        return $this->createErrorResponse(JsonResponse::HTTP_NOT_ACCEPTABLE, $message);
    }

    protected function internalServerError(string $message) : JsonResponse
    {
        return $this->createErrorResponse(JsonResponse::HTTP_INTERNAL_SERVER_ERROR, $message);
    }

    protected function unsupportedMediaType(string $message) : JsonResponse
    {
        return $this->createErrorResponse(JsonResponse::HTTP_UNSUPPORTED_MEDIA_TYPE, $message);
    }

    protected function createErrorResponse($code, string $message) : JsonResponse{
        $this->addError($code, $message);

        return $this->createResponse($code);
    }

    protected function createResponse(int $code) : JsonResponse
    {
        if ($code != JsonResponse::HTTP_NO_CONTENT) {
            $this->addToJsonapi('version', config('jsonapi.version'));
        }

        if ($this->hasMultiErrors()) {
            foreach ($this->jsonResponse['errors'] as $error) {
                $tmp = intval($error['status']);
                if ($tmp >= 400 && $tmp < 500) {
                    $code = 400;
                } elseif ($tmp >= 500) {
                    $code = 500;
                }
            }
        }

        return response()->json($this->jsonResponse, $code);
    }

    /* -------------------- JSON:API response setter / getter ---------------------------------- */

    protected function getResponseMember(string $keyString)
    {
        $keys = explode('.', $keyString);
        $ret = $this->jsonResponse;
        foreach ($keys as $key) {
            if (!isset($ret[$key])) {
                throw new JsonApiException(sprintf('Member "%s" not found in %s.', $key, $keyString));
            }
            $ret = $ret[$key];
        }

        return $ret;
    }

    private function addToMember($member, $key, $value)
    {
        if (!isset($this->jsonResponse[$member])) {
            $this->jsonResponse[$member] = [];
        }
        $this->jsonResponse[$member][$key] = $value;
    }

    protected function setMeta($meta)
    {
        $this->jsonResponse['meta'] = $meta;
    }

    protected function addToMeta($key, $value)
    {
        return $this->addToMember('meta', $key, $value);
    }

    protected function setData($data)
    {
        $this->jsonResponse['data'] = $data;
    }

    protected function setJsonapi($jsonapi)
    {
        $this->jsonResponse['jsonapi'] = $jsonapi;
    }

    protected function addToJsonapi($key, $value)
    {
        return $this->addToMember('jsonapi', $key, $value);
    }

    protected function setLinks($links)
    {
        $this->jsonResponse['links'] = $links;
    }

    protected function addToLinks($key, $value)
    {
        return $this->addToMember('links', $key, $value);
    }

    protected function setIncluded($included)
    {
        $this->jsonResponse['included'] = $included;
    }

    protected function addToIncluded($obj)
    {
        if (!isset($this->jsonResponse['included'])) {
            $this->jsonResponse['included'] = [];
        }

        $this->jsonResponse['included'][] = $obj;
    }

    protected function hasErrors() : bool
    {
        return isset($this->jsonResponse['errors']) && count($this->jsonResponse['errors']) > 0;
    }

    protected function hasMultiErrors() : bool
    {
        return isset($this->jsonResponse['errors']) && count($this->jsonResponse['errors']) > 1;
    }

    protected function addError($statusCode, $details = null, array $meta = null)
    {
        $error = [
            'status' => strval($statusCode),
            'title' => JsonResponse::$statusTexts[$statusCode]
        ];

        if (!is_null($details)) {
            $error['details'] = $details;
        }
        if (!is_null($meta)) {
            $error['meta'] = $meta;
        }

        if (!isset($this->jsonResponse['errors'])) {
            $this->jsonResponse['errors'] = [];
        }
        $this->jsonResponse['errors'][] = $error;
    }

    protected function addErrorFromException(Exception $e)
    {
        $statusCode = $this->getJsonApiStatusCodeFromException($e);

        $meta = null;
        if (config('app.debug')) {
            if (method_exists($e, 'getCode')) {
                $meta['code'] = $e->getCode();
            }
            if (method_exists($e, 'getTrace')) {
                $meta['trace'] = $e->getTrace();
            }
        }

        $this->addError($statusCode, $e->getMessage(), $meta);

        return $statusCode;
    }

    private function getJsonApiStatusCodeFromException(Exception $e)
    {
        if (method_exists($e, 'getStatusCode')) {
            $statusCode = $e->getStatusCode();
        } else if (property_exists($e, 'status')) {
            $statusCode = $e->status;
        } else {
            $statusCode = JsonResponse::HTTP_INTERNAL_SERVER_ERROR;
        }

        return $statusCode;
    }
}
