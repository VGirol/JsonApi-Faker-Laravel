<?php
namespace VGirol\JsonApiAssert\Tests\Asserts;

use VGirol\JsonApiAssert\Assert as JsonApiAssert;
use VGirol\JsonApiAssert\Tests\TestCase;
use VGirol\JsonApiAssert\Messages;

class ErrorsObjectTest extends TestCase
{
    /**
     * @test
     * @dataProvider validErrorSourceObjectProvider
     */
    public function error_source_object_is_valid($data)
    {
        JsonApiAssert::assertIsValidErrorSourceObject($data);
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
     * @test
     * @dataProvider notValidErrorSourceObjectProvider
     */
    public function error_source_object_is_not_valid($data, $failureMessage)
    {
        $fn = function ($data) {
            JsonApiAssert::assertIsValidErrorSourceObject($data);
        };

        JsonApiAssert::assertTestFail($fn, $failureMessage, $data);
    }

    public function notValidErrorSourceObjectProvider()
    {
        return [
            'not an array' => [
                'error',
                Messages::ERROR_SOURCE_OBJECT_NOT_ARRAY
            ],
            'pointer is not a string' => [
                [
                    'valid' => 'valid',
                    'pointer' => 666
                ],
                Messages::ERROR_SOURCE_POINTER_IS_NOT_STRING
            ],
            'pointer does not start with a /' => [
                [
                    'valid' => 'valid',
                    'pointer' => 'not valid'
                ],
                Messages::ERROR_SOURCE_POINTER_START
            ],
            'parameter is not a string' => [
                [
                    'valid' => 'valid',
                    'parameter' => 666
                ],
                Messages::ERROR_SOURCE_PARAMETER_IS_NOT_STRING
            ]
        ];
    }

    /**
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

        JsonApiAssert::assertIsValidErrorObject($data);
    }

    /**
     * @test
     * @dataProvider notValidErrorObjectProvider
     */
    public function error_object_is_not_valid($data, $failureMessage)
    {
        $fn = function ($data) {
            JsonApiAssert::assertIsValidErrorObject($data);
        };

        JsonApiAssert::assertTestFail($fn, $failureMessage, $data);
    }

    public function notValidErrorObjectProvider()
    {
        return [
            'not an array' => [
                'error',
                Messages::ERROR_OBJECT_NOT_ARRAY
            ],
            'empty array' => [
                [],
                Messages::ERROR_OBJECT_NOT_EMPTY
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
                Messages::ERROR_STATUS_IS_NOT_STRING
            ],
            'code is not a string' => [
                [
                    'code' => 13,
                    'status' => 'ok',
                ],
                Messages::ERROR_CODE_IS_NOT_STRING
            ],
            'title is not a string' => [
                [
                    'title' => 13,
                    'status' => 'ok',
                ],
                Messages::ERROR_TITLE_IS_NOT_STRING
            ],
            'details is not a string' => [
                [
                    'details' => 13,
                    'status' => 'ok',
                ],
                Messages::ERROR_DETAILS_IS_NOT_STRING
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

        JsonApiAssert::assertIsValidErrorsObject($data);
    }

    /**
     * @test
     * @dataProvider notValidErrorsObjectProvider
     */
    public function errors_object_is_not_valid($data, $failureMessage)
    {
        $fn = function ($data) {
            JsonApiAssert::assertIsValidErrorsObject($data);
        };

        JsonApiAssert::assertTestFail($fn, $failureMessage, $data);
    }

    public function notValidErrorsObjectProvider()
    {
        return [
            'not an array' => [
                'error',
                Messages::ERRORS_OBJECT_NOT_ARRAY
            ],
            'not an array of objects' => [
                [
                    'error' => 'not valid'
                ],
                Messages::ERRORS_OBJECT_NOT_ARRAY
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
