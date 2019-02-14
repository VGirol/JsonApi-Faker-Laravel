<?php
namespace VGirol\JsonApi\Tests\Unit\Assert;

use PHPUnit\Framework\Assert as PHPUnit;
use VGirol\JsonApi\Tests\TestCase;
use VGirol\JsonApi\Tools\Assert\JsonApiAssertBase;
use VGirol\JsonApi\Tools\Assert\JsonApiAssertMessages;
use VGirol\JsonApi\Tools\Assert\JsonApiAssertObject;

class JsonApiJsonapiObjectTest extends TestCase
{
    use JsonApiAssertBase, JsonApiAssertObject;

    public function testValidJsonapiObject()
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
     * @dataProvider notValidJsonapiObjectProvider
     */
    public function testNotValidJsonapiObject($data, $failureMessage)
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
