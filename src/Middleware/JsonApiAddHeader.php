<?php

namespace VGirol\JsonApi\Middleware;

use Closure;
use Exception;
use Illuminate\Support\Str;
use VGirol\JsonApi\Tools\Assert\JsonApiAssertMessages;

class JsonApiAddHeader
{
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
        $response = $next($request);

        $mediaType = config('jsonapi.media-type');

        if ($response->headers->has('Content-Type')) {
            $header = $response->headers->get('Content-Type');
            if (!Str::contains($header, $mediaType)) {
                throw new Exception(sprintf(JsonApiAssertMessages::JSONAPI_ERROR_CONTENT_TYPE_HEADER_MISSING, $mediaType));
            }
            $headers = explode(';', $header);
            if (count($headers) > 1) {
                throw new Exception(sprintf(JsonApiAssertMessages::JSONAPI_ERROR_CONTENT_TYPE_HEADER_WITHOUT_PARAMETERS, $mediaType));
            }
        }

        $response->header('Content-Type', $mediaType);

        return $response;
    }
}
