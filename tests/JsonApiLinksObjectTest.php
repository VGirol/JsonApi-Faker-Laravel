<?php
namespace VGirol\JsonApiAssert\Tests;

use VGirol\JsonApiAssert\JsonApiAssert;
use PHPUnit\Framework\Assert as PHPUnit;
use VGirol\JsonApiAssert\Tests\TestCase;
use VGirol\JsonApiAssert\JsonApiAssertMessages;

class JsonApiLinksObjectTest extends TestCase
{
    use JsonApiAssert;

    /**
     * @note
     * @test
     * @dataProvider validLinkObjectProvider
     */
    public function link_object_is_valid($data)
    {
        $this->assertIsValidLinkObject($data);
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
     * @note
     * @test
     * @dataProvider notValidLinkObjectProvider
     */
    public function link_object_is_not_valid($data, $failureMessage)
    {
        $fn = function ($data) {
            $this->assertIsValidLinkObject($data);
        };

        $this->assertTestFail($fn, $failureMessage, $data);
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
                JsonApiAssertMessages::JSONAPI_ERROR_LINK_OBJECT_MISS_HREF_MEMBER
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
     * @note
     * @test
     */
    public function links_object_is_valid()
    {
        $data = [
            'self' => 'url',
            'related' => 'url'
        ];

        $allowed = [ 'self', 'related' ];

        $this->assertIsValidLinksObject($data, $allowed);
    }

    /**
     * @note
     * @test
     * @dataProvider notValidLinksObjectProvider
     */
    public function links_object_is_not_valid($data, $allowed, $failureMessage)
    {
        $fn = function ($data, $allowed) {
            $this->assertIsValidLinksObject($data, $allowed);
        };

        $this->assertTestFail($fn, $failureMessage, $data, $allowed);
    }

    public function notValidLinksObjectProvider()
    {
        return [
            'not an array' => [
                'error',
                ['self', 'related'],
                JsonApiAssertMessages::JSONAPI_ERROR_LINKS_OBJECT_NOT_ARRAY
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
    //     $this->assertIsValidLinksObject($data, $withPagination, $forError);
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
    //         $this->assertIsValidLinksObject($data, $withPagination, $forError);
    //     };

    //     $this->assertTestFail($fn, $failureMessage, $data, $withPagination, $forError);
    // }

    // public function notValidLinksObjectProvider()
    // {
    //     return [
    //         'not an array' => [
    //             'error',
    //             false,
    //             false,
    //             JsonApiAssertMessages::JSONAPI_ERROR_LINKS_OBJECT_NOT_ARRAY
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
