<?php
namespace VGirol\JsonApi\Tests\Unit\Assert;

use PHPUnit\Framework\Assert as PHPUnit;
use VGirol\JsonApi\Tests\TestCase;
use VGirol\JsonApi\Tools\Assert\JsonApiAssertBase;
use VGirol\JsonApi\Tools\Assert\JsonApiAssertObject;
use VGirol\JsonApi\Tools\Assert\JsonApiAssertMessages;

class JsonApiAttributesObjectTest extends TestCase
{
    use JsonApiAssertBase, JsonApiAssertObject;

    public function testValidAttributesObject()
    {
        $data = [
            'key' => 'value'
        ];

        $this->assertIsValidAttributesObject($data);
    }

    /**
     * @dataProvider notValidAttributesObjectProvider
     */
    public function testNotValidAttributesObject($data, $failureMessage)
    {
        $fn = function ($data) {
            $this->assertIsValidAttributesObject($data);
        };

        $this->assertTestFail($fn, $failureMessage, $data);
    }

    public function notValidAttributesObjectProvider()
    {
        return [
            'not an array' => [
                'failed',
                JsonApiAssertMessages::JSONAPI_ERROR_ATTRIBUTES_OBJECT_IS_NOT_ARRAY
            ],
            'key is not valid' => [
                [
                    'key+' => 'value'
                ],
                null
            ],
            'value has forbidden member' => [
                [
                    'key' => [
                        'obj' => 'value',
                        'links' => 'forbidden'
                    ]
                ],
                null
            ]
        ];
    }
}
