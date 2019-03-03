<?php
namespace VGirol\JsonApiAssert\Laravel\Tests\Asserts;

use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\TestResponse;
use VGirol\JsonApiAssert\Laravel\Tests\TestCase;
use VGirol\JsonApiAssert\Assert as JsonApiAssert;

class BadRequestTest extends TestCase
{
    /**
     * @test
     */
    public function response_406()
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

        $response->assertJsonApiResponse406($errors);
    }

    /**
     * @test
     * @dataProvider notValidResponse406
     */
    public function response_406_failed($status, $headers, $content, $errors, $failureMsg)
    {
        $fn = function ($status, $headers, $content, $errors) {
            $response = Response::create(json_encode($content), $status, $headers);
            $response = TestResponse::fromBaseResponse($response);

            $response->assertJsonApiResponse406($errors);
        };

        JsonApiAssert::assertTestFail($fn, $failureMsg, $status, $headers, $content, $errors);
    }

    public function notValidResponse406()
    {
        return [
            'bad status' => [
                412,
                [
                    'Content-Type' => [$this->mediaType]
                ],
                [
                    'errors' => [
                        [
                            'status' => '406',
                            'title' => 'Not Acceptable',
                            'details' => 'description'
                        ]
                    ]
                ],
                [
                    [
                        'status' => '406',
                        'title' => 'Not Acceptable',
                        'details' => 'description'
                    ]
                ],
                null
            ],
            'bad header' => [
                406,
                [
                    'Content-Type' => [$this->mediaType . '; param=value']
                ],
                [
                    'errors' => [
                        [
                            'status' => '406',
                            'title' => 'Not Acceptable',
                            'details' => 'description'
                        ]
                    ]
                ],
                [
                    [
                        'status' => '406',
                        'title' => 'Not Acceptable',
                        'details' => 'description'
                    ]
                ],
                null
            ],
            'no errors member' => [
                406,
                [
                    'Content-Type' => [$this->mediaType]
                ],
                [],
                [
                    [
                        'status' => '406',
                        'title' => 'Not Acceptable',
                        'details' => 'description'
                    ]
                ],
                null
            ],
            'no error' => [
                406,
                [
                    'Content-Type' => [$this->mediaType]
                ],
                [
                    'errors' => []
                ],
                [
                    [
                        'status' => '406',
                        'title' => 'Not Acceptable',
                        'details' => 'description'
                    ]
                ],
                null
            ],
        ];
    }

    /**
     * @test
     */
    public function response_415()
    {
        $status = 415;
        $errors = [
            [
                'status' => strval($status),
                'title' => 'Unsupported Media Type',
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

        $response->assertJsonApiResponse415($errors);
    }

    /**
     * @test
     * @dataProvider notValidResponse415
     */
    public function response_415_failed($status, $headers, $content, $errors, $failureMsg)
    {
        $fn = function ($status, $headers, $content, $errors) {
            $response = Response::create(json_encode($content), $status, $headers);
            $response = TestResponse::fromBaseResponse($response);

            $response->assertJsonApiResponse415($errors);
        };

        JsonApiAssert::assertTestFail($fn, $failureMsg, $status, $headers, $content, $errors);
    }

    public function notValidResponse415()
    {
        return [
            'bad status' => [
                412,
                [
                    'Content-Type' => [$this->mediaType]
                ],
                [
                    'errors' => [
                        [
                            'status' => '415',
                            'title' => 'Unsupported Media Type',
                            'details' => 'description'
                        ]
                    ]
                ],
                [
                    [
                        'status' => '415',
                        'title' => 'Unsupported Media Type',
                        'details' => 'description'
                    ]
                ],
                null
            ],
            'bad header' => [
                415,
                [
                    'Content-Type' => [$this->mediaType . '; param=value']
                ],
                [
                    'errors' => [
                        [
                            'status' => '415',
                            'title' => 'Unsupported Media Type',
                            'details' => 'description'
                        ]
                    ]
                ],
                [
                    [
                        'status' => '415',
                        'title' => 'Unsupported Media Type',
                        'details' => 'description'
                    ]
                ],
                null
            ]
        ];
    }

    /**
     * @test
     */
    public function response_404()
    {
        $status = 404;
        $errors = [
            [
                'status' => strval($status),
                'title' => 'Not Found',
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

        $response->assertJsonApiResponse404($errors);
    }

    /**
     * @test
     * @dataProvider notValidResponse404
     */
    public function response_404_failed($status, $headers, $content, $errorDetails, $failureMsg)
    {
        $fn = function ($status, $headers, $content, $errorDetails) {
            $response = Response::create(json_encode($content), $status, $headers);
            $response = TestResponse::fromBaseResponse($response);

            $response->assertJsonApiResponse404($errorDetails);
        };

        JsonApiAssert::assertTestFail($fn, $failureMsg, $status, $headers, $content, $errorDetails);
    }

    public function notValidResponse404()
    {
        return [
            'bad status' => [
                412,
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
                'description',
                null
            ],
            'bad header' => [
                404,
                [
                    'Content-Type' => [$this->mediaType . '; param=value']
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
                'description',
                null
            ],
            'no errors member' => [
                404,
                [
                    'Content-Type' => [$this->mediaType]
                ],
                [],
                'description',
                null
            ],
            'no error' => [
                404,
                [
                    'Content-Type' => [$this->mediaType]
                ],
                [
                    'errors' => []
                ],
                'description',
                null
            ],
        ];
    }

}
