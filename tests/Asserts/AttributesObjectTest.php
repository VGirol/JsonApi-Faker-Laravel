<?php
namespace VGirol\JsonApiAssert\Tests\Asserts;

use VGirol\JsonApiAssert\Assert as JsonApiAssert;
use VGirol\JsonApiAssert\Tests\TestCase;
use VGirol\JsonApiAssert\Messages;

class AttributesObjectTest extends TestCase
{
    /**
     * @test
     */
    public function attributes_object_is_valid()
    {
        $data = [
            'key' => 'value'
        ];

        JsonApiAssert::assertIsValidAttributesObject($data);
    }

    /**
     * @test
     * @dataProvider notValidAttributesObjectProvider
     */
    public function attributes_object_is_not_valid($data, $failureMessage)
    {
        $fn = function ($data) {
            JsonApiAssert::assertIsValidAttributesObject($data);
        };

        JsonApiAssert::assertTestFail($fn, $failureMessage, $data);
    }

    public function notValidAttributesObjectProvider()
    {
        return [
            'not an array' => [
                'failed',
                Messages::ATTRIBUTES_OBJECT_IS_NOT_ARRAY
            ],
            'key is not valid' => [
                [
                    'key+' => 'value'
                ],
                null
            ],
            'field has forbidden member' => [
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
