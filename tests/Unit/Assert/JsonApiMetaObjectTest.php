<?php
namespace VGirol\JsonApi\Tests\Unit\Assert;

use PHPUnit\Framework\Assert as PHPUnit;
use VGirol\JsonApi\Tests\TestCase;
use VGirol\JsonApi\Tools\Assert\JsonApiAssertBase;
use VGirol\JsonApi\Tools\Assert\JsonApiAssertMessages;
use VGirol\JsonApi\Tools\Assert\JsonApiAssertObject;

class JsonApiMetaObjectTest extends TestCase
{
    use JsonApiAssertBase, JsonApiAssertObject;

    public function testValidMetaObject()
    {
        $data = [
            'key' => 'value',
            'another' => 'member'
        ];

        $this->assertIsValidMetaObject($data);
    }

    /**
     * @dataProvider notValidMetaObjectProvider
     */
    public function testNotValidMetaObject($data, $failureMessage)
    {
        $fn = function ($response) {
            $this->assertIsValidMetaObject($response);
        };

        $this->assertTestFail($fn, $failureMessage, $data);
    }

    public function notValidMetaObjectProvider()
    {
        return [
            'not an array' => [
                'failed',
                JsonApiAssertMessages::JSONAPI_ERROR_META_OBJECT_IS_NOT_ARRAY
            ],
            'key is not valid' => [
                [
                    'key+' => 'value'
                ],
                null
            ]
        ];
    }
}
