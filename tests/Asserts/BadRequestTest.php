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
        $errorDetails = 'description';
        $content = [
            'errors' => [
                [
                    'status' => strval($status),
                    'title' => 'Not Acceptable',
                    'details' => $errorDetails
                ]
            ]
        ];
        $headers = [
            'Content-Type' => [$this->mediaType]
        ];

        $response = Response::create(json_encode($content), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $response->assertJsonApiResponse406($errorDetails);
    }

    /**
     * @test
     * @dataProvider notValidResponse406
     */
    public function response_406_failed($status, $headers, $content, $errorDetails, $failureMsg)
    {
        $fn = function ($status, $headers, $content, $errorDetails) {
            $response = Response::create(json_encode($content), $status, $headers);
            $response = TestResponse::fromBaseResponse($response);

            $response->assertJsonApiResponse406($errorDetails);
        };

        JsonApiAssert::assertTestFail($fn, $failureMsg, $status, $headers, $content, $errorDetails);
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
                'description',
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
                'description',
                null
            ],
            'no errors member' => [
                415,
                [
                    'Content-Type' => [$this->mediaType]
                ],
                [],
                'description',
                null
            ],
            'no error' => [
                415,
                [
                    'Content-Type' => [$this->mediaType . '; param=value']
                ],
                [
                    'errors' => []
                ],
                'description',
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
        $errorDetails = 'description';
        $content = [
            'errors' => [
                [
                    'status' => strval($status),
                    'title' => 'Unsupported Media Type',
                    'details' => $errorDetails
                ]
            ]
        ];
        $headers = [
            'Content-Type' => [$this->mediaType]
        ];

        $response = Response::create(json_encode($content), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $response->assertJsonApiResponse415($errorDetails);
    }

    /**
     * @test
     * @dataProvider notValidResponse415
     */
    public function response_415_failed($status, $headers, $content, $errorDetails, $failureMsg)
    {
        $fn = function ($status, $headers, $content, $errorDetails) {
            $response = Response::create(json_encode($content), $status, $headers);
            $response = TestResponse::fromBaseResponse($response);

            $response->assertJsonApiResponse415($errorDetails);
        };

        JsonApiAssert::assertTestFail($fn, $failureMsg, $status, $headers, $content, $errorDetails);
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
                'description',
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
                'description',
                null
            ]
        ];
    }
}
