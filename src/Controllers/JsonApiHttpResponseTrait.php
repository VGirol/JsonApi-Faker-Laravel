<?php
namespace VGirol\JsonApi\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;
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
        return $this->createResponse(JsonResponse::HTTP_NO_CONTENT);
    }

    protected function badRequest(string $message) : JsonResponse
    {
        $code = JsonResponse::HTTP_BAD_REQUEST;
        $this->addError($code, $message);

        return $this->createResponse($code);
    }

    protected function unauthorized(string $message) : JsonResponse
    {
        $code= JsonResponse::HTTP_UNAUTHORIZED;
        $this->addError($code, $message);

        return $this->createResponse($code);
    }

    protected function notFound(string $message) : JsonResponse
    {
        $code = JsonResponse::HTTP_NOT_FOUND;
        $this->addError($code, $message);

        return $this->createResponse($code);
    }

    protected function internalServerError(string $message) : JsonResponse
    {
        $code = JsonResponse::HTTP_INTERNAL_SERVER_ERROR;
        $this->addError($code, $message);

        return $this->createResponse($code);
    }

    protected function createResponse(int $code) : JsonResponse
    {
        $this->addToJsonapi('version', '1.0');

        return response()->json($this->jsonResponse, $code)
                         ->header('Content-Type', 'application/vnd.api+json');
    }

    /* -------------------- JSON:API response setter / getter ---------------------------------- */

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
