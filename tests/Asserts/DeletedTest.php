<?php
namespace VGirol\JsonApiAssert\Laravel\Tests\Asserts;

use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\TestResponse;
use VGirol\JsonApiAssert\Laravel\Tests\TestCase;
use VGirol\JsonApiAssert\Assert as JsonApiAssert;

class DeletedTest extends TestCase
{
    /**
     * @test
     */
    public function response_deleted()
    {
        $status = 200;
        $content = [
            'meta' => [
                'message' => 'Deleting succeed'
            ]
        ];
        $headers = [
            'Content-Type' => [$this->mediaType]
        ];

        $response = Response::create(json_encode($content), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $response->assertJsonApiDeleted();
    }

    /**
     * @test
     * @dataProvider notValidResponseDeleted
     */
    public function response_deleted_failed($status, $headers, $content, $failureMsg)
    {
        $fn = function ($status, $headers, $content) {
            $response = Response::create(json_encode($content), $status, $headers);
            $response = TestResponse::fromBaseResponse($response);

            $response->assertJsonApiDeleted();
        };

        JsonApiAssert::assertTestFail($fn, $failureMsg, $status, $headers, $content);
    }

    public function notValidResponseDeleted()
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
            'meta not valid' => [
                200,
                [
                    'Content-Type' => [$this->mediaType]
                ],
                [
                    'meta' => [
                        'result+' => 'failed'
                    ]
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
}
