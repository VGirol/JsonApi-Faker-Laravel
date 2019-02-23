<?php
namespace VGirol\JsonApiAssert\Tests\Asserts;

use VGirol\JsonApiAssert\Assert as JsonApiAssert;
use VGirol\JsonApiAssert\Tests\TestCase;
use VGirol\JsonApiAssert\Messages;

class JsonapiObjectTest extends TestCase
{
    /**
     * @test
     */
    public function jsonapi_object_is_valid()
    {
        $data = [
            'version' => 'jsonapi v1.1',
            'meta' => [
                'allowed' => 'valid'
            ]
        ];

        JsonApiAssert::assertIsValidJsonapiObject($data);
    }

    /**
     * @test
     * @dataProvider notValidJsonapiObjectProvider
     */
    public function jsonapi_object_is_not_valid($data, $failureMessage)
    {
        $fn = function ($data) {
            JsonApiAssert::assertIsValidJsonapiObject($data);
        };

        JsonApiAssert::assertTestFail($fn, $failureMessage, $data);
    }

    public function notValidJsonapiObjectProvider()
    {
        return [
            'not an array' => [
                'error',
                Messages::OBJECT_NOT_ARRAY
            ],
            'not allowed member' => [
                [
                    'version' => 'jsonapi 1.0',
                    'not' => 'allowed'
                ],
                null
            ],
            'version is not a string' => [
                [
                    'version' => 123
                ],
                null
            ],
            'meta not valid' => [
                [
                    'version' => 'jsonapi 1.0',
                    'meta' => 'not valid'
                ],
                null
            ]
        ];
    }
}
