<?php
namespace VGirol\JsonApi\Tests\Unit\Check;

use PHPUnit\Framework\Assert as PHPUnit;

trait JsonApiMemberNameTest
{
    public function testValidMemberName()
    {
        $data = 'valid';

        $this->checkMemberName($data);
    }

    /**
     * @depends testValidMemberName
     * @dataProvider notValidMemberNameProvider
     */
    public function testNotValidMemberName($data, $failureMessage)
    {
        $fn = function ($data) {
            $this->checkMemberName($data);
        };

        $this->assertTestFail($fn, $failureMessage, $data);
    }

    public function notValidMemberNameProvider()
    {
        return [
            'not a string' => [
                123,
                static::$JSONAPI_ERROR_MEMBER_NAME_IS_NOT_STRING
            ]
        ];
    }

    /**
     * @dataProvider validFieldProvider
     */
    public function testValidField($data)
    {
        $this->checkField($data);
    }

    public function validFieldProvider()
    {
        return [
            'an object' => [
                [
                    'validkey' => 'validvalue'
                ]
            ],
            'an array of objects' => [
                [
                    ['validkey' => 'validvalue'],
                    ['validkey2' => 'validvalue2']
                ]
            ]
        ];
    }

    /**
     * @depends testValidField
     * @dataProvider notValidFieldProvider
     */
    public function testNotValidField($data, $failureMessage)
    {
        $fn = function ($data) {
            $this->checkField($data);
        };

        $this->assertTestFail($fn, $failureMessage, $data);
    }

    public function notValidFieldProvider()
    {
        return [
            'not valid' => [
                [
                    'links' => 'not allowed member name'
                ],
                static::$JSONAPI_ERROR_MEMBER_NAME_NOT_ALLOWED
            ],
            'not valid (complex)' => [
                [
                    'meta' => 'ok',
                    'obj' => [
                        'safe' => 'ok',
                        'links' => 'not allowed member name'
                    ]
                ],
                static::$JSONAPI_ERROR_MEMBER_NAME_NOT_ALLOWED
            ]
        ];
    }
}
