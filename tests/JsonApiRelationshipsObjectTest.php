<?php
namespace VGirol\JsonApiAssert\Tests;

use VGirol\JsonApiAssert\JsonApiAssert;
use PHPUnit\Framework\Assert as PHPUnit;
use VGirol\JsonApiAssert\Tests\TestCase;
use VGirol\JsonApiAssert\JsonApiAssertMessages;

class JsonApiRelationshipsObjectTest extends TestCase
{
    use JsonApiAssert;

    /**
     * @note
     * @test
     * @dataProvider validResourceLinkageProvider
     */
    public function resource_linkage_is_valid($data)
    {
        $this->assertIsValidResourceLinkage($data);
    }

    public function validResourceLinkageProvider()
    {
        return [
            'null' => [
                null
            ],
            'empty array' => [
                []
            ],
            'single resource identifier object' => [
                [
                    'type' => 'people',
                    'id' => '9'
                ]
            ],
            'array of resource identifier objects' => [
                [
                    [
                        'type' => 'people',
                        'id' => '9'
                    ],
                    [
                        'type' => 'people',
                        'id' => '10'
                    ]
                ]
            ]
        ];
    }

    /**
     * @note
     * @test
     * @dataProvider notValidResourceLinkageProvider
     */
    public function resource_linkage_is_not_valid($data, $failureMessage)
    {
        $fn = function ($data) {
            $this->assertIsValidResourceLinkage($data);
        };

        $this->assertTestFail($fn, $failureMessage, $data);
    }

    public function notValidResourceLinkageProvider()
    {
        return [
            'not an array' => [
                'not valid',
                JsonApiAssertMessages::JSONAPI_ERROR_RESOURCE_LINKAGE_NOT_ARRAY
            ],
            'not valid single resource identifier object' => [
                [
                    'type' => 'people',
                    'id' => '9',
                    'anything' => 'not valid'
                ],
                null
            ],
            'not valid array of resource identifier objects' => [
                [
                    [
                        'type' => 'people',
                        'id' => '9',
                        'anything' => 'not valid'
                    ],
                    [
                        'type' => 'people',
                        'id' => '10'
                    ]
                ],
                null
            ]
        ];
    }

    /**
     * @note
     * @test
     * @dataProvider validRelationshipObjectProvider
     */
    public function relationship_object_is_valid($data)
    {
        $this->assertIsValidRelationshipObject($data);
    }

    public function validRelationshipObjectProvider()
    {
        return [
            'short' => [
                [
                    'data' => [
                        'type' => 'test',
                        'id' => '2'
                    ]
                ]
            ],
            'long' => [
                [
                    'data' => [
                        [
                            'type' => 'author',
                            'id' => '2'
                        ],
                        [
                            'type' => 'author',
                            'id' => '3'
                        ]
                    ],
                    'links' => [
                        'self' => 'http://example.com/articles/1/relationships/author',
                        'related' => 'http://example.com/articles/1/author',
                        'first' => 'url',
                        'next' => 'url'
                    ],
                    'meta' => [
                        'anything' => 'valid'
                    ]
                ]
            ]
        ];
    }

    /**
     * @note
     * @test
     * @dataProvider notValidRelationshipObjectProvider
     */
    public function relationship_object_is_not_valid($data, $failureMessage)
    {
        $fn = function ($data) {
            $this->assertIsValidRelationshipObject($data);
        };

        $this->assertTestFail($fn, $failureMessage, $data);
    }

    public function notValidRelationshipObjectProvider()
    {
        return [
            'mandatory member miss' => [
                [
                    'anything' => [
                        'not' => 'valid'
                    ]
                ],
                null
            ],
            'array of resource identifier objects without pagination' => [
                [
                    [
                        'data' => [
                            [
                                'type' => 'test',
                                'id' => '2'
                            ],
                            [
                                'type' => 'test',
                                'id' => '3'
                            ]
                        ],
                        'links' => [
                            'self' => 'url',
                            'related' => 'url'
                        ]
                    ]
                ],
                null
            ]
        ];
    }

    /**
     * @note
     * @test
     */
    public function relationships_object_is_valid()
    {
        $data = [
            'author' => [
                'links' => [
                    'self' => 'http://example.com/articles/1/relationships/author',
                    'related' => 'http://example.com/articles/1/author'
                ],
                'data' => [
                    'type' => 'people',
                    'id' => '9'
                ]
            ]
        ];

        $this->assertIsValidRelationshipsObject($data);
    }

    /**
     * @note
     * @test
     * @dataProvider notValidRelationshipsObjectProvider
     */
    public function relationships_object_is_not_valid($data, $failureMessage)
    {
        $fn = function ($data) {
            $this->assertIsValidRelationshipsObject($data);
        };

        $this->assertTestFail($fn, $failureMessage, $data);
    }

    public function notValidRelationshipsObjectProvider()
    {
        return [
            'an array of objects' => [
                [
                    ['test' => 'not valid'],
                    ['anything' => 'not valid']
                ],
                null
            ],
            'no valid member name' => [
                [
                    'author+' => [
                        'data' => [
                            'type' => 'people',
                            'id' => '9'
                        ]
                    ]
                ],
                null
            ]
        ];
    }
}
