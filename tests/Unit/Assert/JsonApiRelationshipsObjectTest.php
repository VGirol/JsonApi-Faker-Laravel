<?php
namespace VGirol\JsonApi\Tests\Unit\Assert;

use PHPUnit\Framework\Assert as PHPUnit;
use VGirol\JsonApi\Tests\TestCase;
use VGirol\JsonApi\Tools\Assert\JsonApiAssertBase;
use VGirol\JsonApi\Tools\Assert\JsonApiAssertMessages;
use VGirol\JsonApi\Tools\Assert\JsonApiAssertObject;

class JsonApiRelationshipsObjectTest extends TestCase
{
    use JsonApiAssertBase, JsonApiAssertObject;

    /**
     * @dataProvider validResourceLinkageProvider
     */
    public function testValidResourceLinkage($data)
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
     * @dataProvider notValidResourceLinkageProvider
     */
    public function testNotValidResourceLinkage($data, $failureMessage)
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
     * @dataProvider validRelationshipObjectProvider
     */
    public function testValidRelationshipObject($data)
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
                        'related' => 'url',
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
     * @dataProvider notValidRelationshipObjectProvider
     */
    public function testNotValidRelationshipObject($data, $failureMessage)
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

    public function testValidRelationshipsObject()
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
     * @dataProvider notValidRelationshipsObjectProvider
     */
    public function testNotValidRelationshipsObject($data, $failureMessage)
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
