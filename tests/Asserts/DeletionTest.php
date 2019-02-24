<?php
namespace VGirol\JsonApiAssert\Laravel\Tests\Asserts;

use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\TestResponse;
use VGirol\JsonApiAssert\Laravel\Tests\TestCase;
use VGirol\JsonApiAssert\Assert as JsonApiAssert;

class DeletionTest extends TestCase
{
    /**
     * @test
     */
    public function response_deletion()
    {
        $status = 200;
        $content = [
            'meta' => [
                'result' => 'it works'
            ]
        ];
        $headers = [
            'Content-Type' => [$this->mediaType]
        ];

        $response = Response::create(json_encode($content), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $response->assertJsonApiDeletion(true);
    }

    /**
     * @test
     * @dataProvider notValidResponseDeletion
     */
    public function response_deletion_failed($status, $headers, $content, $failureMsg)
    {
        $fn = function ($status, $headers, $content) {
            $response = Response::create(json_encode($content), $status, $headers);
            $response = TestResponse::fromBaseResponse($response);

            $response->assertJsonApiDeletion(true);
        };

        JsonApiAssert::assertTestFail($fn, $failureMsg, $status, $headers, $content);
    }

    public function notValidResponseDeletion()
    {
        return [
            'resource not found' => [
                404,
                [
                    'Content-Type' => [$this->mediaType]
                ],
                [
                    'errors' => [
                        [
                            'status' => '404',
                            'title' => 'Not Found',
                            'details' => 'description'
                        ]
                    ]
                ],
                'Expected status code 200 but received 404.'
            ],
            'bad header' => [
                200,
                [
                    'Content-Type' => [$this->mediaType.'; param=value']
                ],
                [
                    'meta' => [
                        'result' => 'it works'
                    ]
                ],
                null
            ],
            'no meta' => [
                200,
                [
                    'Content-Type' => [$this->mediaType]
                ],
                [
                    'links' => []
                ],
                null
            ],
            'not only meta' => [
                200,
                [
                    'Content-Type' => [$this->mediaType]
                ],
                [
                    'links' => [],
                    'meta' => [
                        'result' => 'it works'
                    ]
                ],
                null
            ]
        ];
    }

    /**
     * @test
     */
    public function response_deletion_with_no_content()
    {
        $status = 204;
        $content = '';
        $headers = [
            'Content-Type' => [$this->mediaType]
        ];

        $response = Response::create(json_encode($content), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $response->assertJsonApiDeletion(false);
    }

    /**
     * @test
     * @dataProvider notValidResponseDeletionWithNoContent
     */
    public function response_deletion_with_no_content_failed($status, $headers, $content, $failureMsg)
    {
        $fn = function ($status, $headers, $content) {
            $response = Response::create($content, $status, $headers);
            $response = TestResponse::fromBaseResponse($response);

            $response->assertJsonApiDeletion(false);
        };

        JsonApiAssert::assertTestFail($fn, $failureMsg, $status, $headers, $content);
    }

    public function notValidResponseDeletionWithNoContent()
    {
        return [
            'resource not found' => [
                404,
                [
                    'Content-Type' => [$this->mediaType]
                ],
                [
                    'errors' => [
                        [
                            'status' => '404',
                            'title' => 'Not Found',
                            'details' => 'description'
                        ]
                    ]
                ],
                'Expected status code 204 but received 404.'
            ],
            'bad header' => [
                204,
                [
                    'Content-Type' => [$this->mediaType.'; param=value']
                ],
                '',
                null
            ],
            'has content' => [
                204,
                [
                    'Content-Type' => [$this->mediaType]
                ],
                [
                    'links' => []
                ],
                null
            ]
        ];
    }
}
