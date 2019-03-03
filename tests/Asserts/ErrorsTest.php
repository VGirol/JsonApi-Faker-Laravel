<?php
namespace VGirol\JsonApiAssert\Laravel\Tests\Asserts;

use Illuminate\Http\Response;
use VGirol\JsonApiAssert\Messages;
use Illuminate\Foundation\Testing\TestResponse;
use VGirol\JsonApiAssert\Laravel\Tests\TestCase;
use VGirol\JsonApiAssert\Assert as JsonApiAssert;

class ErrorsTest extends TestCase
{
    /**
     * @test
     */
    public function response_with_errors()
    {
        $status = 406;
        $errors = [
            [
                'status' => strval($status),
                'title' => 'Not Acceptable',
                'details' => 'description'
            ]
        ];
        $content = [
            'errors' => $errors
        ];
        $headers = [
            'Content-Type' => [$this->mediaType]
        ];

        $response = Response::create(json_encode($content), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $response->assertJsonApiErrorResponse($status, $errors);
    }

    /**
     * @test
     * @dataProvider notValidResponseWithErrors
     */
    public function response_with_errors_failed($content, $expectedErrors, $failureMsg)
    {
        $fn = function ($content, $expectedErrors) {
            $status = 404;
            $headers = [
                'Content-Type' => [$this->mediaType]
            ];
            $response = Response::create(json_encode($content), $status, $headers);
            $response = TestResponse::fromBaseResponse($response);

            $response->assertJsonApiErrorResponse($status, $expectedErrors);
        };

        JsonApiAssert::assertTestFail($fn, $failureMsg, $content, $expectedErrors);
    }

    public function notValidResponseWithErrors()
    {
        return [
            'no errors member' => [
                [
                    'meta' => [
                        'bad' => 'response'
                    ]
                ],
                [
                    [
                        'status' => '404',
                        'title' => 'Not Found',
                        'details' => 'description'
                    ]
                ],
                sprintf(Messages::HAS_MEMBER, 'errors')
            ],
            'not valid errors member' => [
                [
                    'errors' => [
                        'bad' => 'response'
                    ]
                ],
                [
                    [
                        'status' => '404',
                        'title' => 'Not Found',
                        'details' => 'description'
                    ]
                ],
                Messages::ERRORS_OBJECT_NOT_ARRAY
            ],
            'not valid expected errors' => [
                [
                    'errors' => [
                        [
                            'status' => '404',
                            'title' => 'Not Found',
                            'details' => 'description'
                        ]
                    ]
                ],
                [
                    'bad' => 'response'
                ],
                null
            ],
            'not enougth errors' => [
                [
                    'errors' => [
                        [
                            'status' => '404',
                            'title' => 'Not Found',
                            'details' => 'description'
                        ]
                    ]
                ],
                [
                    [
                        'status' => '404',
                        'title' => 'Not Found',
                        'details' => 'description'
                    ],
                    [
                        'status' => '404',
                        'title' => 'Not Found',
                        'details' => 'description'
                    ]
                ],
                null
            ],
            'errors not valid' => [
                [
                    'errors' => [
                        [
                            'status' => '410',
                            'title' => 'Not Found',
                            'details' => 'description'
                        ],
                        [
                            'status' => '415',
                            'title' => 'Not Found',
                            'details' => 'description'
                        ]
                    ]
                ],
                [
                    [
                        'status' => '404',
                        'title' => 'Not Found',
                        'details' => 'description'
                    ]
                ],
                null
            ],
        ];
    }
}
