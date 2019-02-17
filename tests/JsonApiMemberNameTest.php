<?php
namespace VGirol\JsonApiAssert\Tests;

use VGirol\JsonApiAssert\JsonApiAssert;
use PHPUnit\Framework\Assert as PHPUnit;
use VGirol\JsonApiAssert\Tests\TestCase;
use VGirol\JsonApiAssert\JsonApiAssertMessages;

class JsonApiMemberNameTest extends TestCase
{
    use JsonApiAssert;

    /**
     * @note
     * @test
     * @dataProvider validMemberNameProvider
     */
    public function member_name_is_valid($data, $strict)
    {
        $data = 'valid-member';

        $this->assertIsValidMemberName($data);
    }

    public function validMemberNameProvider()
    {
        return [
            'not strict' => [
                'valid member',
                false
            ],
            'strict' => [
                'valid-member',
                true
            ]
        ];
    }

    /**
     * @note
     * @test
     * @dataProvider notValidMemberNameProvider
     */
    public function member_name_is_not_valid($data, $strict, $failureMessage)
    {
        $fn = function ($data, $strict) {
            $this->assertIsValidMemberName($data, $strict);
        };

        $this->assertTestFail($fn, $failureMessage, $data, $strict);
    }

    public function notValidMemberNameProvider()
    {
        return [
            'not a string' => [
                123,
                false,
                JsonApiAssertMessages::JSONAPI_ERROR_MEMBER_NAME_IS_NOT_STRING
            ],
            'too short' => [
                '',
                false,
                JsonApiAssertMessages::JSONAPI_ERROR_MEMBER_NAME_IS_TOO_SHORT
            ],
            'strict mode' => [
                'not valid',
                true,
                JsonApiAssertMessages::JSONAPI_ERROR_MEMBER_NAME_HAVE_RESERVED_CHARACTERS
            ],
            'reserved characters' => [
                'az-F%3_t',
                false,
                JsonApiAssertMessages::JSONAPI_ERROR_MEMBER_NAME_HAVE_RESERVED_CHARACTERS
            ],
            'start with not globally allowed character' => [
                '_az',
                false,
                JsonApiAssertMessages::JSONAPI_ERROR_MEMBER_NAME_START_AND_END_WITH_ALLOWED_CHARACTERS
            ],
            'end with not globally allowed character' => [
                'az_',
                false,
                JsonApiAssertMessages::JSONAPI_ERROR_MEMBER_NAME_START_AND_END_WITH_ALLOWED_CHARACTERS
            ]
        ];
    }

    /**
     * @note
     * @test
     */
    public function member_name_is_not_forbidden()
    {
        $name = 'valid';
        $this->assertIsNotForbiddenMemberName($name);
    }

    /**
     * @note
     * @test
     * @dataProvider forbiddenMemberNameProvider
     */
    public function member_name_is_forbidden($data, $failureMessage)
    {
        $fn = function ($data) {
            $this->assertIsNotForbiddenMemberName($data);
        };

        $this->assertTestFail($fn, $failureMessage, $data);
    }

    public function forbiddenMemberNameProvider()
    {
        return [
            'relationships' => [
                'relationships',
                JsonApiAssertMessages::JSONAPI_ERROR_MEMBER_NAME_NOT_ALLOWED
            ],
            'links' => [
                'links',
                JsonApiAssertMessages::JSONAPI_ERROR_MEMBER_NAME_NOT_ALLOWED
            ]
        ];
    }

    // /**
    //  * @dataProvider validFieldProvider
    //  */
    // public function testValidField($data)
    // {
    //     $this->assertHasNoForbiddenMemberName($data);
    // }

    // public function validFieldProvider()
    // {
    //     return [
    //         'an object' => [
    //             [
    //                 'validkey' => 'validvalue'
    //             ]
    //         ],
    //         'an array of objects' => [
    //             [
    //                 ['validkey' => 'validvalue'],
    //                 ['validkey2' => 'validvalue2']
    //             ]
    //         ]
    //     ];
    // }

    // /**
    //  * @dataProvider notValidFieldProvider
    //  */
    // public function testNotValidField($data, $failureMessage)
    // {
    //     $fn = function ($data) {
    //         $this->assertHasNoForbiddenMemberName($data);
    //     };

    //     $this->assertTestFail($fn, $failureMessage, $data);
    // }

    // public function notValidFieldProvider()
    // {
    //     return [
    //         'not valid' => [
    //             [
    //                 'links' => 'not allowed member name'
    //             ],
    //             JsonApiAssertMessages::JSONAPI_ERROR_MEMBER_NAME_NOT_ALLOWED
    //         ],
    //         'not valid (complex)' => [
    //             [
    //                 'meta' => 'ok',
    //                 'obj' => [
    //                     'safe' => 'ok',
    //                     'links' => 'not allowed member name'
    //                 ]
    //             ],
    //             JsonApiAssertMessages::JSONAPI_ERROR_MEMBER_NAME_NOT_ALLOWED
    //         ]
    //     ];
    // }
}
