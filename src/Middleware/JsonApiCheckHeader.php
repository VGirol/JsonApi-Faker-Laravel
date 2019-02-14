<?php

namespace VGirol\JsonApi\Middleware;

use Closure;
use Illuminate\Support\Str;
use VGirol\JsonApi\Tools\Assert\JsonApiAssertMessages;
use VGirol\JsonApi\Controllers\JsonApiHttpResponseTrait;

class JsonApiCheckHeader
{
    use JsonApiHttpResponseTrait;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $mediaType = config('jsonapi.media-type');

        // Content-Type header
        if (!$request->hasHeader('Content-Type')) {
            return $this->notAcceptable(sprintf(JsonApiAssertMessages::JSONAPI_ERROR_CONTENT_TYPE_HEADER_MISSING, $mediaType));
        }

        $contentType = $request->header('Content-Type');
        $count = preg_match_all('/' . preg_quote($mediaType, '/') . '[;]?(.*)/', $contentType, $matches);
        if ($count === false) {
            throw new \Exception();
        }
        if ($count == 0) {
            return $this->unsupportedMediaType(sprintf(JsonApiAssertMessages::JSONAPI_ERROR_CONTENT_TYPE_HEADER_MISSING, $mediaType));
        } else {
            $param = $matches[1][0];
            if ($param != '') {
                return $this->unsupportedMediaType(sprintf(JsonApiAssertMessages::JSONAPI_ERROR_CONTENT_TYPE_HEADER_WITHOUT_PARAMETERS, $mediaType));
            }
        }

        // Accept header
        if ($request->hasHeader('Accept')) {
            $accept = $request->header('Accept');
            $count = preg_match_all('/' . preg_quote($mediaType, '/') . '[;]?([^,]*)/', $accept, $matches);
            if ($count === false) {
                throw new \Exception();
            }
            if ($count >= 1) {
                $check = false;
                for ($i = 0; $i < $count; $i++) {
                    $param = $matches[1][$i];
                    if ($param == '') {
                        $check = true;
                    }
                }
                if (!$check) {
                    return $this->notAcceptable(sprintf(JsonApiAssertMessages::JSONAPI_ERROR_ACCEPT_HEADER_WITHOUT_PARAMETERS, $mediaType));
                }
            }
        }

        return $next($request);
    }
}
