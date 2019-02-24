<?php
namespace VGirol\JsonApiAssert\Tests\Response;

use VGirol\JsonApiAssert\Assert as JsonApiAssert;
use VGirol\JsonApiAssert\Tests\TestCase;
use VGirol\JsonApiAssert\AssertResponse as JsonApiAssertResponse;

class BadRequestTest extends TestCase
{

    protected $mediaType = 'application/vnd.api+json';

    /**
     * @test
     */
    public function response_headers()
    {
        $headers = [
            'Content-Type' => [ $this->mediaType ]
        ];

        JsonApiAssertResponse::assertResponseHeaders($headers);
    }

    /**
     * @test
     * @dataProvider notValidResponseHeaders
     */
    public function response_headers_failed($headers, $failureMsg)
    {
        $fn = function ($headers) {
            JsonApiAssertResponse::assertResponseHeaders($headers);
        };

        JsonApiAssert::assertTestFail($fn, $failureMsg, $headers);
    }

    public function notValidResponseHeaders()
    {
        return [
            'no content-type header' => [
                [],
                null
            ],
            'content-type header with bad media type' => [
                [ 'Content-Type' => [ 'application/json' ] ],
                null
            ],
            'content-type header with parameter' => [
                [ 'Content-Type' => [ $this->mediaType.'; param=value' ] ],
                null
            ]
        ];
    }

    /**
     * @test
     */
    public function response_406()
    {
        $response = [
            'status' => 406,
            'content' => [
                'errors' => [
                    [
                        'status' => '406',
                        'title' => 'Not Acceptable',
                        'details' => 'description'
                    ]
                ]
            ],
            'headers' => [
                'Content-Type' => [ $this->mediaType ]
            ]
        ];

        JsonApiAssertResponse::assertResponse406($response);
    }

    /**
     * @test
     * @dataProvider notValidResponse406
     */
    public function response_406_failed($response, $failureMsg)
    {
        $fn = function ($response) {
            JsonApiAssertResponse::assertResponse406($response);
        };

        JsonApiAssert::assertTestFail($fn, $failureMsg, $response);
    }

    public function notValidResponse406()
    {
        return [
            'bad status' => [
                [
                    'status' => 412,
                    'content' => [
                        'errors' => [
                            [
                                'status' => '406',
                                'title' => 'Not Acceptable',
                                'details' => 'description'
                            ]
                        ]
                    ],
                    'headers' => [
                        'Content-Type' => [ $this->mediaType ]
                    ]
                ],
                null
            ],
            'bad header' => [
                [
                    'status' => 415,
                    'content' => [
                        'errors' => [
                            [
                                'status' => '415',
                                'title' => 'Unsupported Media Type',
                                'details' => 'description'
                            ]
                        ]
                    ],
                    'headers' => [
                        'Content-Type' => [ $this->mediaType.'; param=value' ]
                    ]
                ],
                null
            ],
            'no errors member' => [
                [
                    'status' => 415,
                    'content' => [
                    ],
                    'headers' => [
                        'Content-Type' => [ $this->mediaType.'; param=value' ]
                    ]
                ],
                null
            ],
            'no error' => [
                [
                    'status' => 415,
                    'content' => [
                        'errors' => []
                    ],
                    'headers' => [
                        'Content-Type' => [ $this->mediaType.'; param=value' ]
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
        $response = [
            'status' => 415,
            'content' => [
                'errors' => [
                    [
                        'status' => '415',
                        'title' => 'Unsupported Media Type',
                        'details' => 'description'
                    ]
                ]
            ],
            'headers' => [
                'Content-Type' => [ $this->mediaType ]
            ]
        ];

        JsonApiAssertResponse::assertResponse415($response);
    }

    /**
     * @test
     * @dataProvider notValidResponse415
     */
    public function response_415_failed($response, $failureMsg)
    {
        $fn = function ($response) {
            JsonApiAssertResponse::assertResponse415($response);
        };

        JsonApiAssert::assertTestFail($fn, $failureMsg, $response);
    }

    public function notValidResponse415()
    {
        return [
            'bad status' => [
                [
                    'status' => 412,
                    'content' => [
                        'errors' => [
                            [
                                'status' => '415',
                                'title' => 'Unsupported Media Type',
                                'details' => 'description'
                            ]
                        ]
                    ],
                    'headers' => [
                        'Content-Type' => [ $this->mediaType ]
                    ]
                ],
                null
            ],
            'bad header' => [
                [
                    'status' => 415,
                    'content' => [
                        'errors' => [
                            [
                                'status' => '415',
                                'title' => 'Unsupported Media Type',
                                'details' => 'description'
                            ]
                        ]
                    ],
                    'headers' => [
                        'Content-Type' => [ $this->mediaType.'; param=value' ]
                    ]
                ],
                null
            ]
        ];
    }
}
