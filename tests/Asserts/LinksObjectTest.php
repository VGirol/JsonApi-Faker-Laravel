<?php
namespace VGirol\JsonApiAssert\Tests\Asserts;

use VGirol\JsonApiAssert\Assert as JsonApiAssert;
use VGirol\JsonApiAssert\Tests\TestCase;
use VGirol\JsonApiAssert\Messages;

class LinksObjectTest extends TestCase
{
    /**
     * @test
     * @dataProvider validLinkObjectProvider
     */
    public function link_object_is_valid($data)
    {
        JsonApiAssert::assertIsValidLinkObject($data);
    }

    public function validLinkObjectProvider()
    {
        return [
            'null value' => [
                null
            ],
            'as string' => [
                'validLink'
            ],
            'as object' => [
                [
                    'href' => 'validLink',
                    'meta' => [
                        'key' => 'value'
                    ]
                ]
            ]
        ];
    }

    /**
     * @test
     * @dataProvider notValidLinkObjectProvider
     */
    public function link_object_is_not_valid($data, $failureMessage)
    {
        $fn = function ($data) {
            JsonApiAssert::assertIsValidLinkObject($data);
        };

        JsonApiAssert::assertTestFail($fn, $failureMessage, $data);
    }

    public function notValidLinkObjectProvider()
    {
        return [
            'not an array' => [
                666,
                null
            ],
            'no "href" member' => [
                [
                    'meta' => 'error'
                ],
                Messages::LINK_OBJECT_MISS_HREF_MEMBER
            ],
            'not only allowed members' => [
                [
                    'href' => 'valid',
                    'meta' => 'valid',
                    'test' => 'error'
                ],
                null
            ],
            'meta not valid' => [
                [
                    'href' => 'valid',
                    'meta' => 666
                ],
                null
            ]
        ];
    }

    /**
     * @test
     */
    public function links_object_is_valid()
    {
        $data = [
            'self' => 'url',
            'related' => 'url'
        ];

        $allowed = [ 'self', 'related' ];

        JsonApiAssert::assertIsValidLinksObject($data, $allowed);
    }

    /**
     * @test
     * @dataProvider notValidLinksObjectProvider
     */
    public function links_object_is_not_valid($data, $allowed, $failureMessage)
    {
        $fn = function ($data, $allowed) {
            JsonApiAssert::assertIsValidLinksObject($data, $allowed);
        };

        JsonApiAssert::assertTestFail($fn, $failureMessage, $data, $allowed);
    }

    public function notValidLinksObjectProvider()
    {
        return [
            'not an array' => [
                'error',
                ['self', 'related'],
                Messages::LINKS_OBJECT_NOT_ARRAY
            ],
            'not only allowed members' => [
                [
                    'self' => 'valid',
                    'first' => 'valid',
                    'test' => 'error'
                ],
                ['self', 'related'],
                null
            ],
            'link not valid' => [
                [
                    'self' => 666
                ],
                ['self', 'related'],
                null
            ]
        ];
    }

    // /**
    //  * @note
    //  * @test
    //  * @dataProvider validLinksObjectProvider
    //  */
    // public function links_object_is_valid($data, $withPagination, $forError)
    // {
    //     JsonApiAssert::assertIsValidLinksObject($data, $withPagination, $forError);
    // }

    // public function validLinksObjectProvider()
    // {
    //     return [
    //         'short' => [
    //             [
    //                 'self' => 'url'
    //             ],
    //             false,
    //             false
    //         ],
    //         'with pagination' => [
    //             [
    //                 'self' => 'url',
    //                 'related' => 'url',
    //                 'first' => 'url',
    //                 'last' => 'url',
    //                 'next' => 'url',
    //                 'prev' => 'url'
    //             ],
    //             true,
    //             false
    //         ],
    //         'for error' => [
    //             [
    //                 'about' => 'url',
    //             ],
    //             false,
    //             true
    //         ]
    //     ];
    // }

    // /**
    //  * @note
    //  * @test
    //  * @dataProvider notValidLinksObjectProvider
    //  */
    // public function links_object_is_not_valid($data, $withPagination, $forError, $failureMessage)
    // {
    //     $fn = function ($data, $withPagination, $forError) {
    //         JsonApiAssert::assertIsValidLinksObject($data, $withPagination, $forError);
    //     };

    //     JsonApiAssert::assertTestFail($fn, $failureMessage, $data, $withPagination, $forError);
    // }

    // public function notValidLinksObjectProvider()
    // {
    //     return [
    //         'not an array' => [
    //             'error',
    //             false,
    //             false,
    //             Messages::LINKS_OBJECT_NOT_ARRAY
    //         ],
    //         'pagination not allowed' => [
    //             [
    //                 'self' => 'valid',
    //                 'first' => 'valid',
    //             ],
    //             false,
    //             false,
    //             null
    //         ],
    //         'not only allowed members' => [
    //             [
    //                 'self' => 'valid',
    //                 'first' => 'valid',
    //                 'test' => 'error'
    //             ],
    //             true,
    //             false,
    //             null
    //         ],
    //         'link not valid' => [
    //             [
    //                 'self' => 666
    //             ],
    //             false,
    //             false,
    //             null
    //         ],
    //         'link error' => [
    //             [
    //                 'error' => 'not about member'
    //             ],
    //             false,
    //             true,
    //             null
    //         ]
    //     ];
    // }
}
