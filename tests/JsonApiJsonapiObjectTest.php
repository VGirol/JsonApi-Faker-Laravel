<?php
namespace VGirol\JsonApiAssert\Tests;

use VGirol\JsonApiAssert\JsonApiAssert;
use PHPUnit\Framework\Assert as PHPUnit;
use VGirol\JsonApiAssert\Tests\TestCase;
use VGirol\JsonApiAssert\JsonApiAssertMessages;

class JsonApiJsonapiObjectTest extends TestCase
{
    use JsonApiAssert;

    /**
     * @note
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

        $this->assertIsValidJsonapiObject($data);
    }

    /**
     * @note
     * @test
     * @dataProvider notValidJsonapiObjectProvider
     */
    public function jsonapi_object_is_not_valid($data, $failureMessage)
    {
        $fn = function ($data) {
            $this->assertIsValidJsonapiObject($data);
        };

        $this->assertTestFail($fn, $failureMessage, $data);
    }

    public function notValidJsonapiObjectProvider()
    {
        return [
            'not an array' => [
                'error',
                JsonApiAssertMessages::JSONAPI_ERROR_JSONAPI_OBJECT_NOT_ARRAY
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
