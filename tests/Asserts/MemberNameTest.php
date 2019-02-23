<?php
namespace VGirol\JsonApiAssert\Tests\Asserts;

use VGirol\JsonApiAssert\Assert as JsonApiAssert;
use VGirol\JsonApiAssert\Tests\TestCase;
use VGirol\JsonApiAssert\Messages;

class MemberNameTest extends TestCase
{
    /**
     * @test
     * @dataProvider validMemberNameProvider
     */
    public function member_name_is_valid($data, $strict)
    {
        $data = 'valid-member';

        JsonApiAssert::assertIsValidMemberName($data);
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
     * @test
     * @dataProvider notValidMemberNameProvider
     */
    public function member_name_is_not_valid($data, $strict, $failureMessage)
    {
        $fn = function ($data, $strict) {
            JsonApiAssert::assertIsValidMemberName($data, $strict);
        };

        JsonApiAssert::assertTestFail($fn, $failureMessage, $data, $strict);
    }

    public function notValidMemberNameProvider()
    {
        return [
            'not a string' => [
                123,
                false,
                Messages::MEMBER_NAME_IS_NOT_STRING
            ],
            'too short' => [
                '',
                false,
                Messages::MEMBER_NAME_IS_TOO_SHORT
            ],
            'strict mode' => [
                'not valid',
                true,
                Messages::MEMBER_NAME_HAVE_RESERVED_CHARACTERS
            ],
            'reserved characters' => [
                'az-F%3_t',
                false,
                Messages::MEMBER_NAME_HAVE_RESERVED_CHARACTERS
            ],
            'start with not globally allowed character' => [
                '_az',
                false,
                Messages::MEMBER_NAME_START_AND_END_WITH_ALLOWED_CHARACTERS
            ],
            'end with not globally allowed character' => [
                'az_',
                false,
                Messages::MEMBER_NAME_START_AND_END_WITH_ALLOWED_CHARACTERS
            ]
        ];
    }

    /**
     * @test
     */
    public function member_name_is_not_forbidden()
    {
        $name = 'valid';
        JsonApiAssert::assertIsNotForbiddenMemberName($name);
    }

    /**
     * @test
     * @dataProvider forbiddenMemberNameProvider
     */
    public function member_name_is_forbidden($data, $failureMessage)
    {
        $fn = function ($data) {
            JsonApiAssert::assertIsNotForbiddenMemberName($data);
        };

        JsonApiAssert::assertTestFail($fn, $failureMessage, $data);
    }

    public function forbiddenMemberNameProvider()
    {
        return [
            'relationships' => [
                'relationships',
                Messages::MEMBER_NAME_NOT_ALLOWED
            ],
            'links' => [
                'links',
                Messages::MEMBER_NAME_NOT_ALLOWED
            ]
        ];
    }

    // /**
    //  * @dataProvider validFieldProvider
    //  */
    // public function testValidField($data)
    // {
    //     JsonApiAssert::assertHasNoForbiddenMemberName($data);
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
    //         JsonApiAssert::assertHasNoForbiddenMemberName($data);
    //     };

    //     JsonApiAssert::assertTestFail($fn, $failureMessage, $data);
    // }

    // public function notValidFieldProvider()
    // {
    //     return [
    //         'not valid' => [
    //             [
    //                 'links' => 'not allowed member name'
    //             ],
    //             Messages::MEMBER_NAME_NOT_ALLOWED
    //         ],
    //         'not valid (complex)' => [
    //             [
    //                 'meta' => 'ok',
    //                 'obj' => [
    //                     'safe' => 'ok',
    //                     'links' => 'not allowed member name'
    //                 ]
    //             ],
    //             Messages::MEMBER_NAME_NOT_ALLOWED
    //         ]
    //     ];
    // }
}
