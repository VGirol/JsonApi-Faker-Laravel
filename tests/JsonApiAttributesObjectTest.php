<?php
namespace VGirol\JsonApiAssert\Tests;

use VGirol\JsonApiAssert\JsonApiAssert;
use PHPUnit\Framework\Assert as PHPUnit;
use VGirol\JsonApiAssert\Tests\TestCase;
use VGirol\JsonApiAssert\JsonApiAssertMessages;

class JsonApiAttributesObjectTest extends TestCase
{
    use JsonApiAssert;

    /**
     * @note
     * @test
     */
    public function attributes_object_is_valid()
    {
        $data = [
            'key' => 'value'
        ];

        $this->assertIsValidAttributesObject($data);
    }

    /**
     * @note
     * @test
     * @dataProvider notValidAttributesObjectProvider
     */
    public function attributes_object_is_not_valid($data, $failureMessage)
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
