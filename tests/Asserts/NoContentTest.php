<?php
namespace VGirol\JsonApiAssert\Laravel\Tests\Asserts;

use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\TestResponse;
use VGirol\JsonApiAssert\Laravel\Tests\TestCase;
use VGirol\JsonApiAssert\Assert as JsonApiAssert;

class NoContentTest extends TestCase
{
    /**
     * @test
     */
    public function response_no_content()
    {
        $headers = [
            'X-PERSONAL' => ['test']
        ];

        $response = Response::create(null, 204, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $response->assertJsonApiNoContent();
    }

    /**
     * @test
     * @dataProvider notValidResponseNoContent
     */
    public function response_no_content_failed($status, $headers, $content, $failureMsg)
    {
        $fn = function ($status, $headers, $content) {
            $response = Response::create(json_encode($content), $status, $headers);
            $response = TestResponse::fromBaseResponse($response);

            $response->assertJsonApiNoContent();
        };

        JsonApiAssert::assertTestFail($fn, $failureMsg, $status, $headers, $content);
    }

    public function notValidResponseNoContent()
    {
        return [
            'bad status' => [
                201,
                [],
                null,
                'Expected status code 204 but received 201.'
            ],
            'has header' => [
                204,
                [
                    'Content-Type' => [$this->mediaType]
                ],
                null,
                'Unexpected header [Content-Type] is present on response.'
            ],
            'has content' => [
                204,
                [],
                [
                    'meta' => [
                        'bad' => 'content'
                    ]
                ],
                null
            ]
        ];
    }
}
