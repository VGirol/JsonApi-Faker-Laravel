<?php
namespace VGirol\JsonApiAssert\Tests;

use VGirol\JsonApiAssert\JsonApiAssert;
use PHPUnit\Framework\Assert as PHPUnit;
use VGirol\JsonApiAssert\Tests\TestCase;
use VGirol\JsonApiAssert\JsonApiAssertMessages;

class JsonApiMetaObjectTest extends TestCase
{
    use JsonApiAssert;

    /**
     * @note
     * @test
     */
    public function meta_object_is_valid()
    {
        $data = [
            'key' => 'value',
            'another' => 'member'
        ];

        $this->assertIsValidMetaObject($data);
    }

    /**
     * @note
     * @test
     * @dataProvider notValidMetaObjectProvider
     */
    public function meta_object_is_not_valid($data, $failureMessage)
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
            'array of objects' => [
                [
                    [ 'first' => 'element' ],
                    [ 'second' => 'element' ]
                ],
                JsonApiAssertMessages::JSONAPI_ERROR_META_OBJECT_IS_NOT_ARRAY
            ],
            'key is not valid' => [
                [
                    'key+' => 'value'
                ],
                JsonApiAssertMessages::JSONAPI_ERROR_MEMBER_NAME_HAVE_RESERVED_CHARACTERS
            ]
        ];
    }
}
