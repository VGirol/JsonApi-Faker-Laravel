<?php
namespace VGirol\JsonApi\Tests\Unit\Check;

use PHPUnit\Framework\Assert as PHPUnit;

trait JsonApiMetaObjectTest
{
    public function testValidMetaObject()
    {
        $data = [
            'key' => 'value',
            'another' => 'member'
        ];

        $this->checkMetaObject($data);
    }

    /**
     * @dataProvider notValidMetaObjectProvider
     */
    public function testNotValidMetaObject($data, $failureMessage)
    {
        $fn = function ($response) {
            $this->checkMetaObject($response);
        };

        $this->assertTestFail($fn, $failureMessage, $data);
    }

    public function notValidMetaObjectProvider()
    {
        return [
            'not an array' => [
                'failed',
                static::$JSONAPI_ERROR_META_OBJECT_IS_NOT_ARRAY
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
