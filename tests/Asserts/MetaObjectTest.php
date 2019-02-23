<?php
namespace VGirol\JsonApiAssert\Tests\Asserts;

use VGirol\JsonApiAssert\Assert as JsonApiAssert;
use VGirol\JsonApiAssert\Tests\TestCase;
use VGirol\JsonApiAssert\Messages;

class MetaObjectTest extends TestCase
{
    /**
     * @test
     */
    public function meta_object_is_valid()
    {
        $data = [
            'key' => 'value',
            'another' => 'member'
        ];

        JsonApiAssert::assertIsValidMetaObject($data);
    }

    /**
     * @test
     * @dataProvider notValidMetaObjectProvider
     */
    public function meta_object_is_not_valid($data, $failureMessage)
    {
        $fn = function ($response) {
            JsonApiAssert::assertIsValidMetaObject($response);
        };

        JsonApiAssert::assertTestFail($fn, $failureMessage, $data);
    }

    public function notValidMetaObjectProvider()
    {
        return [
            'not an array' => [
                'failed',
                Messages::META_OBJECT_IS_NOT_ARRAY
            ],
            'array of objects' => [
                [
                    [ 'first' => 'element' ],
                    [ 'second' => 'element' ]
                ],
                Messages::META_OBJECT_IS_NOT_ARRAY
            ],
            'key is not valid' => [
                [
                    'key+' => 'value'
                ],
                Messages::MEMBER_NAME_HAVE_RESERVED_CHARACTERS
            ]
        ];
    }
}
