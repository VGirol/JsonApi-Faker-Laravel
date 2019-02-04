<?php
namespace VGirol\JsonApi\Tests\Unit\Check;

use PHPUnit\Framework\Assert as PHPUnit;

trait JsonApiJsonapiObjectTest
{
    public function testValidJsonapiObject()
    {
        $data = [
            'version' => 'jsonapi v1.1',
            'meta' => [
                'allowed' => 'valid'
            ]
        ];

        $this->checkJsonapiObject($data);
    }

    /**
     * @dataProvider notValidJsonapiObjectProvider
     */
    public function testNotValidJsonapiObject($data, $failureMessage)
    {
        $fn = function ($data) {
            $this->checkJsonapiObject($data);
        };

        $this->assertTestFail($fn, $failureMessage, $data);
    }

    public function notValidJsonapiObjectProvider()
    {
        return [
            'not an array' => [
                'error',
                static::$JSONAPI_ERROR_JSONAPI_OBJECT_NOT_ARRAY
            ],
            'not allowed member' => [
                [
                    'version' => 'jsonapi 1.0',
                    'not' => 'allowed'
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
