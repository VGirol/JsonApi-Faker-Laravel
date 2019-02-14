<?php
namespace VGirol\JsonApi\Tests\Unit\Middleware;

use Illuminate\Http\Request;
use PHPUnit\Framework\Assert as PHPUnit;
use VGirol\JsonApi\Tests\TestCase;
use VGirol\JsonApi\Middleware\JsonApiCheckHeader;

class JsonApiCheckHeaderTest extends TestCase
{
    public function testValidContentTypeHeader()
    {
        $mediaType = config('jsonapi.media-type');

        $request = new Request();
        $request->headers->set('Content-Type', $mediaType);

        $middleware = new JsonApiCheckHeader;
        $response = $middleware->handle($request, function ($req) {
            PHPUnit::assertTrue(true);
            return null;
        });

        PHPUnit::assertNull($response);
    }

    public function testValidAcceptHeader()
    {
        $mediaType = config('jsonapi.media-type');

        $request = new Request();
        $request->headers->set('Content-Type', $mediaType);
        $request->headers->set('Accept', "{$mediaType}, application/json, {$mediaType}; charset=utf-8");

        $middleware = new JsonApiCheckHeader;
        $response = $middleware->handle($request, function ($req) {
            PHPUnit::assertTrue(true);
            return null;
        });

        PHPUnit::assertNull($response);
    }

    public function testMissingContentTypeHeader()
    {
        $request = new Request();

        $middleware = new JsonApiCheckHeader;
        $response = $middleware->handle($request, function () {
            return null;
        });

        // Check response status code
        PHPUnit::assertNotNull($response);
        PHPUnit::assertEquals($response->getStatusCode(), 406);
    }

    public function testContentTypeHeaderWithBadMediaType()
    {
        $request = new Request();
        $request->headers->set('Content-Type', 'application/json');

        $middleware = new JsonApiCheckHeader;
        $response = $middleware->handle($request, function () {
            return null;
        });

        // Check response status code
        PHPUnit::assertNotNull($response);
        PHPUnit::assertEquals($response->getStatusCode(), 415);
    }

    public function testContentTypeHeaderWithMediaTypeParameter()
    {
        $mediaType = config('jsonapi.media-type');

        $request = new Request();
        $request->headers->set('Content-Type', "{$mediaType}; charset=utf-8");

        $middleware = new JsonApiCheckHeader;
        $response = $middleware->handle($request, function () {
            return null;
        });

        // Check response status code
        PHPUnit::assertNotNull($response);
        PHPUnit::assertEquals($response->getStatusCode(), 415);
    }

    public function testNotValidAcceptHeader()
    {
        $mediaType = config('jsonapi.media-type');

        $request = new Request();
        $request->headers->set('Content-Type', $mediaType);
        $request->headers->set('Accept', "{$mediaType}; param=value, application/json, {$mediaType}; charset=utf-8");

        $middleware = new JsonApiCheckHeader;
        $response = $middleware->handle($request, function ($req) {
            return null;
        });

        PHPUnit::assertNotNull($response);
        PHPUnit::assertEquals($response->getStatusCode(), 406);
    }
}
