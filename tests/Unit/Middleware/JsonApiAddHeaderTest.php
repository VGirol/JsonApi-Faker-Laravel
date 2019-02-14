<?php
namespace VGirol\JsonApi\Tests\Unit\Middleware;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use VGirol\JsonApi\Tests\TestCase;
use PHPUnit\Framework\Assert as PHPUnit;
use VGirol\JsonApi\Middleware\JsonApiAddHeader;
use VGirol\JsonApi\Tools\Assert\JsonApiAssertMessages;

class JsonApiAddHeaderTest extends TestCase
{
    public function testResponseHasValidHeader()
    {
        $mediaType = config('jsonapi.media-type');

        $request = new Request();
        $request->headers->set('Content-Type', $mediaType);

        $response = new Response;

        $request = Request::create('/', 'GET');

        $middleware = new JsonApiAddHeader;
        $response = $middleware->handle($request, function () use ($response) {
            return $response;
        });

        // Check response status code
        PHPUnit::assertNotNull($response);
        $this->assertTrue($response->headers->contains('Content-Type', $mediaType));
    }

    public function testResponseContentTypeHasBadValue()
    {
        $mediaType = config('jsonapi.media-type');

        $request = new Request();
        $request->headers->set('Content-Type', $mediaType);

        $response = new Response;
        $response->header('Content-Type', 'application/json');

        $request = Request::create('/', 'GET');

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage(sprintf(JsonApiAssertMessages::JSONAPI_ERROR_CONTENT_TYPE_HEADER_MISSING, $mediaType));

        $middleware = new JsonApiAddHeader;
        $response = $middleware->handle($request, function () use ($response) {
            return $response;
        });
    }

    public function testResponseContentTypeHasParameters()
    {
        $mediaType = config('jsonapi.media-type');

        $request = new Request();
        $request->headers->set('Content-Type', $mediaType);

        $response = new Response;
        $response->header('Content-Type', "{$mediaType}; param=value");

        $request = Request::create('/', 'GET');

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage(sprintf(JsonApiAssertMessages::JSONAPI_ERROR_CONTENT_TYPE_HEADER_WITHOUT_PARAMETERS, $mediaType));

        $middleware = new JsonApiAddHeader;
        $response = $middleware->handle($request, function () use ($response) {
            return $response;
        });
    }
}
