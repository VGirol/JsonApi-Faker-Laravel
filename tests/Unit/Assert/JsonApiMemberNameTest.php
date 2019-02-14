<?php
namespace VGirol\JsonApi\Tests\Unit\Assert;

use PHPUnit\Framework\Assert as PHPUnit;
use VGirol\JsonApi\Tests\TestCase;
use VGirol\JsonApi\Tools\Assert\JsonApiAssertBase;
use VGirol\JsonApi\Tools\Assert\JsonApiAssertMessages;
use VGirol\JsonApi\Tools\Assert\JsonApiAssertObject;

class JsonApiMemberNameTest extends TestCase
{
    use JsonApiAssertBase, JsonApiAssertObject;

    public function testValidMemberName()
    {
        $data = 'valid';

        $this->assertIsValidMemberName($data);
    }

    /**
     * @dataProvider notValidMemberNameProvider
     */
    public function testNotValidMemberName($data, $failureMessage)
    {
        $fn = function ($data) {
            $this->assertIsValidMemberName($data);
        };

        $this->assertTestFail($fn, $failureMessage, $data);
    }

    public function notValidMemberNameProvider()
    {
        return [
            'not a string' => [
                123,
                JsonApiAssertMessages::JSONAPI_ERROR_MEMBER_NAME_IS_NOT_STRING
            ],
            'too short' => [
                '',
                JsonApiAssertMessages::JSONAPI_ERROR_MEMBER_NAME_IS_TOO_SHORT
            ],
            'reserved characters' => [
                'az-F%3_t',
                JsonApiAssertMessages::JSONAPI_ERROR_MEMBER_NAME_HAVE_RESERVED_CHARACTERS
            ],
            'start with not globally allowed character' => [
                '_az',
                JsonApiAssertMessages::JSONAPI_ERROR_MEMBER_NAME_START_AND_END_WITH_ALLOWED_CHARACTERS
            ],
            'end with not globally allowed character' => [
                'az_',
                JsonApiAssertMessages::JSONAPI_ERROR_MEMBER_NAME_START_AND_END_WITH_ALLOWED_CHARACTERS
            ]
        ];
    }

    /**
     * @dataProvider validFieldProvider
     */
    public function testValidField($data)
    {
        $this->assertIsValidField($data);
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
     * @dataProvider notValidFieldProvider
     */
    public function testNotValidField($data, $failureMessage)
    {
        $fn = function ($data) {
            $this->assertIsValidField($data);
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
                JsonApiAssertMessages::JSONAPI_ERROR_MEMBER_NAME_NOT_ALLOWED
            ],
            'not valid (complex)' => [
                [
                    'meta' => 'ok',
                    'obj' => [
                        'safe' => 'ok',
                        'links' => 'not allowed member name'
                    ]
                ],
                JsonApiAssertMessages::JSONAPI_ERROR_MEMBER_NAME_NOT_ALLOWED
            ]
        ];
    }
}
