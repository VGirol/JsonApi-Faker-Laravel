<?php
namespace VGirol\Assert\Tests;

use VGirol\JsonApiAssert\JsonApiAssert;
use PHPUnit\Framework\Assert as PHPUnit;
use VGirol\JsonApiAssert\Tests\TestCase;
use VGirol\JsonApiAssert\JsonApiAssertMessages;

class JsonApiErrorsObjectTest extends TestCase
{
    use JsonApiAssert;

    /**
     * @dataProvider validErrorSourceObjectProvider
     */
    public function error_source_object_is_valid($data)
    {
        $this->assertIsValidErrorSourceObject($data);
    }

    public function validErrorSourceObjectProvider()
    {
        return [
            'short' => [
                [
                    'anything' => 'blabla'
                ]
            ],
            'long' => [
                [
                    'anything' => 'blabla',
                    'pointer' => '/data/attributes/title',
                    'parameter' => 'blabla'
                ]
            ]
        ];
    }

    /**
     * @note
     * @test
     * @dataProvider notValidErrorSourceObjectProvider
     */
    public function error_source_object_is_not_valid($data, $failureMessage)
    {
        $fn = function ($data) {
            $this->assertIsValidErrorSourceObject($data);
        };

        $this->assertTestFail($fn, $failureMessage, $data);
    }

    public function notValidErrorSourceObjectProvider()
    {
        return [
            'not an array' => [
                'error',
                JsonApiAssertMessages::JSONAPI_ERROR_ERROR_SOURCE_OBJECT_NOT_ARRAY
            ],
            'pointer is not a string' => [
                [
                    'valid' => 'valid',
                    'pointer' => 666
                ],
                JsonApiAssertMessages::JSONAPI_ERROR_ERROR_SOURCE_POINTER_IS_NOT_STRING
            ],
            'pointer does not start with a /' => [
                [
                    'valid' => 'valid',
                    'pointer' => 'not valid'
                ],
                JsonApiAssertMessages::JSONAPI_ERROR_ERROR_SOURCE_POINTER_START
            ],
            'parameter is not a string' => [
                [
                    'valid' => 'valid',
                    'parameter' => 666
                ],
                JsonApiAssertMessages::JSONAPI_ERROR_ERROR_SOURCE_PARAMETER_IS_NOT_STRING
            ]
        ];
    }

    /**
     * @note
     * @test
     */
    public function error_object_is_valid()
    {
        $data = [
            'id' => 15,
            'links' => [
                'about' => 'url'
            ],
            'status' => 'test',
            'code' => 'E13',
            'title' => 'test',
            'details' => 'test',
            'source' => [
                'anything' => 'valid',
                'pointer' => '/data/type'
            ],
            'meta' => [
                'anything' => 'valid'
            ]
        ];

        $this->assertIsValidErrorObject($data);
    }

    /**
     * @dataProvider notValidErrorObjectProvider
     */
    public function error_object_is_not_valid($data, $failureMessage)
    {
        $fn = function ($data) {
            $this->assertIsValidErrorObject($data);
        };

        $this->assertTestFail($fn, $failureMessage, $data);
    }

    public function notValidErrorObjectProvider()
    {
        return [
            'not an array' => [
                'error',
                JsonApiAssertMessages::JSONAPI_ERROR_ERROR_OBJECT_NOT_ARRAY
            ],
            'empty array' => [
                [],
                JsonApiAssertMessages::JSONAPI_ERROR_ERROR_OBJECT_NOT_EMPTY
            ],
            'not allowed member' => [
                [
                    'code' => 'E13',
                    'not' => 'not valid',
                ],
                null
            ],
            'status is not a string' => [
                [
                    'code' => 'E13',
                    'status' => 666,
                ],
                JsonApiAssertMessages::JSONAPI_ERROR_ERROR_STATUS_IS_NOT_STRING
            ],
            'code is not a string' => [
                [
                    'code' => 13,
                    'status' => 'ok',
                ],
                JsonApiAssertMessages::JSONAPI_ERROR_ERROR_CODE_IS_NOT_STRING
            ],
            'title is not a string' => [
                [
                    'title' => 13,
                    'status' => 'ok',
                ],
                JsonApiAssertMessages::JSONAPI_ERROR_ERROR_TITLE_IS_NOT_STRING
            ],
            'details is not a string' => [
                [
                    'details' => 13,
                    'status' => 'ok',
                ],
                JsonApiAssertMessages::JSONAPI_ERROR_ERROR_DETAILS_IS_NOT_STRING
            ],
            'source is not valid' => [
                [
                    'status' => 'ok',
                    'source' => 'not valid'
                ],
                null
            ],
            'links is not valid' => [
                [
                    'status' => 'ok',
                    'links' => [
                        'no' => 'not valid'
                    ]
                ],
                null
            ],
            'meta is not valid' => [
                [
                    'status' => 'ok',
                    'meta' => [
                        'not+' => 'not valid'
                    ]
                ],
                null
            ]
        ];
    }

    /**
     * @note
     * @test
     */
    public function errors_object_is_valid()
    {
        $data = [
            [
                'status' => 'test',
                'code' => 'E13',
            ],
            [
                'status' => 'test2',
                'code' => 'E132',
            ]
        ];

        $this->assertIsValidErrorsObject($data);
    }

    /**
     * @note
     * @test
     * @dataProvider notValidErrorsObjectProvider
     */
    public function errors_object_is_not_valid($data, $failureMessage)
    {
        $fn = function ($data) {
            $this->assertIsValidErrorsObject($data);
        };

        $this->assertTestFail($fn, $failureMessage, $data);
    }

    public function notValidErrorsObjectProvider()
    {
        return [
            'not an array' => [
                'error',
                JsonApiAssertMessages::JSONAPI_ERROR_ERRORS_OBJECT_NOT_ARRAY
            ],
            'not an array of objects' => [
                [
                    'error' => 'not valid'
                ],
                JsonApiAssertMessages::JSONAPI_ERROR_ERRORS_OBJECT_NOT_ARRAY
            ],
            'error object not valid' => [
                [
                    [
                        'code' => 'E13',
                        '+not' => 'not valid',
                    ]
                ],
                null
            ]
        ];
    }
}
