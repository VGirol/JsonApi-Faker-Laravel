<?php
namespace VGirol\JsonApi\Tests\Unit\Check;

use PHPUnit\Framework\Assert as PHPUnit;

trait JsonApiAttributesObjectTest
{
    public function testValidAttributesObject()
    {
        $data = [
            'key' => 'value'
        ];

        $this->checkAttributes($data);
    }

    /**
     * @dataProvider notValidAttributesObjectProvider
     */
    public function testNotValidAttributesObject($data, $failureMessage)
    {
        $fn = function ($data) {
            $this->checkAttributes($data);
        };

        $this->assertTestFail($fn, $failureMessage, $data);
    }

    public function notValidAttributesObjectProvider()
    {
        return [
            'not an array' => [
                'failed',
                static::$JSONAPI_ERROR_ATTRIBUTES_OBJECT_IS_NOT_ARRAY
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
