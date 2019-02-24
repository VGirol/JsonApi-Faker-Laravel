<?php
namespace VGirol\JsonApiAssert\Tests\Asserts;

use VGirol\JsonApiAssert\Assert as JsonApiAssert;
use VGirol\JsonApiAssert\Tests\TestCase;
use VGirol\JsonApiAssert\Messages;

class ResourceObjectTest extends TestCase
{
    /**
     * @test
     */
    public function resource_has_valid_top_level_structure()
    {
        $data = [
            'id' => '1',
            'type' => 'articles',
            'attributes' => [
                'title' => 'test'
            ],
            'links' => [
                'self' => '/articles/1'
            ],
            'meta' => [
                'member' => 'is valid'
            ],
            'relationships' => [
                'author' => [
                    'links' => [
                        'self' => '/articles/1/relationships/author',
                        'related' => '/articles/1/author'
                    ],
                    'data' => [
                        'type' => 'people',
                        'id' => '9'
                    ]
                ]
            ]
        ];

        JsonApiAssert::assertResourceObjectHasValidTopLevelStructure($data);
    }

    /**
     * @test
     * @dataProvider hasotValidTopLevelStructureProvider
     */
    public function resource_has_not_valid_top_level_structure($data, $failureMessage)
    {
        $fn = function ($response) {
            JsonApiAssert::assertResourceObjectHasValidTopLevelStructure($response);
        };

        JsonApiAssert::assertTestFail($fn, $failureMessage, $data);
    }

    public function hasotValidTopLevelStructureProvider()
    {
        return [
            'not an array' => [
                'failed',
                Messages::RESOURCE_IS_NOT_ARRAY
            ],
            'id is missing' => [
                [
                    'type' => 'test',
                    'attributes' => [
                        'attr' => 'value'
                    ]
                ],
                Messages::RESOURCE_ID_MEMBER_IS_ABSENT
            ],
            'type is missing' => [
                [
                    'id' => '1',
                    'attributes' => [
                        'attr' => 'value'
                    ]
                ],
                Messages::RESOURCE_TYPE_MEMBER_IS_ABSENT
            ],
            'missing mandatory member' => [
                [
                    'id' => '1',
                    'type' => 'test'
                ],
                null
            ],
            'member not allowed' => [
                [
                    'id' => '1',
                    'type' => 'test',
                    'wrong' => 'wrong'
                ],
                null
            ]
        ];
    }

    /**
     * @test
     */
    public function resource_id_member_is_valid()
    {
        $data = [
            'id' => '1',
            'type' => 'test'
        ];

        JsonApiAssert::assertResourceIdMember($data);
    }

    /**
     * @test
     * @dataProvider notValidResourceIdMemberProvider
     */
    public function resource_id_member_is_not_valid($data, $failureMessage)
    {
        $fn = function ($response) {
            JsonApiAssert::assertResourceIdMember($response);
        };

        JsonApiAssert::assertTestFail($fn, $failureMessage, $data);
    }

    public function notValidResourceIdMemberProvider()
    {
        return [
            'id is empty' => [
                [
                    'id' => '',
                    'type' => 'test'
                ],
                Messages::RESOURCE_ID_MEMBER_IS_EMPTY
            ],
            'id is not a string' => [
                [
                    'id' => 1,
                    'type' => 'test'
                ],
                Messages::RESOURCE_ID_MEMBER_IS_NOT_STRING
            ]
        ];
    }

    /**
     * @test
     */
    public function resource_type_member_is_valid()
    {
        $data = [
            'id' => '1',
            'type' => 'test'
        ];

        JsonApiAssert::assertResourceTypeMember($data);
    }

    /**
     * @test
     * @dataProvider notValidResourceTypeMemberProvider
     */
    public function resource_type_member_is_not_valid($data, $failureMessage)
    {
        $fn = function ($response) {
            JsonApiAssert::assertResourceTypeMember($response);
        };

        JsonApiAssert::assertTestFail($fn, $failureMessage, $data);
    }

    public function notValidResourceTypeMemberProvider()
    {
        return [
            'type is empty' => [
                [
                    'id' => '1',
                    'type' => ''
                ],
                Messages::RESOURCE_TYPE_MEMBER_IS_EMPTY
            ],
            'type is not a string' => [
                [
                    'id' => '1',
                    'type' => 404
                ],
                Messages::RESOURCE_TYPE_MEMBER_IS_NOT_STRING
            ],
            'type value has forbidden characters' => [
                [
                    'id' => '1',
                    'type' => 'test+1'
                ],
                Messages::MEMBER_NAME_HAVE_RESERVED_CHARACTERS
            ]
        ];
    }

    /**
     * @test
     */
    public function resource_identifier_object_is_valid()
    {
        $data = [
            'id' => '1',
            'type' => 'test',
            'meta' => [
                'member' => 'is valid'
            ]
        ];

        JsonApiAssert::assertIsValidResourceIdentifierObject($data);
    }

    /**
     * @test
     * @dataProvider isNotValidResourceIdentifierObjectProvider
     */
    public function resource_identifier_object_is_not_valid($data, $failureMessage)
    {
        $fn = function ($response) {
            JsonApiAssert::assertIsValidResourceIdentifierObject($response);
        };

        JsonApiAssert::assertTestFail($fn, $failureMessage, $data);
    }

    public function isNotValidResourceIdentifierObjectProvider()
    {
        return [
            'not an array' => [
                'failed',
                Messages::RESOURCE_IDENTIFIER_IS_NOT_ARRAY
            ],
            'id is missing' => [
                [
                    'type' => 'test'
                ],
                Messages::RESOURCE_ID_MEMBER_IS_ABSENT
            ],
            'id is not valid' => [
                [
                    'id' => 1,
                    'type' => 'test'
                ],
                Messages::RESOURCE_ID_MEMBER_IS_NOT_STRING
            ],
            'type is missing' => [
                [
                    'id' => '1'
                ],
                Messages::RESOURCE_TYPE_MEMBER_IS_ABSENT
            ],
            'type is not valid' => [
                [
                    'id' => '1',
                    'type' => 404
                ],
                Messages::RESOURCE_TYPE_MEMBER_IS_NOT_STRING
            ],
            'member not allowed' => [
                [
                    'id' => '1',
                    'type' => 'test',
                    'wrong' => 'wrong'
                ],
                null
            ],
            'meta is not valid' => [
                [
                    'id' => '1',
                    'type' => 'test',
                    'meta' => 'wrong'
                ],
                null
            ]
        ];
    }

    /**
     * @test
     */
    public function resource_field_is_valid()
    {
        $data = [
            'id' => '1',
            'type' => 'articles',
            'attributes' => [
                'title' => 'test'
            ],
            'relationships' => [
                'author' => [
                    'data' => [
                        'type' => 'people',
                        'id' => '9'
                    ]
                ]
            ]
        ];

        JsonApiAssert::assertValidFields($data);
    }

    /**
     * @test
     * @dataProvider isNotValidResourceFieldProvider
     */
    public function resource_field_is_not_valid($data, $failureMessage)
    {
        $fn = function ($response) {
            JsonApiAssert::assertValidFields($response);
        };

        JsonApiAssert::assertTestFail($fn, $failureMessage, $data);
    }

    public function isNotValidResourceFieldProvider()
    {
        return [
            'attribute and relationship with the same name' => [
                [
                    'id' => '1',
                    'type' => 'articles',
                    'attributes' => [
                        'title' => 'test'
                    ],
                    'relationships' => [
                        'title' => [
                            'data' => [
                                'type' => 'people',
                                'id' => '9'
                            ]
                        ]
                    ]
                ],
                null
            ],
            'attribute named type or id' => [
                [
                    'id' => '1',
                    'type' => 'articles',
                    'attributes' => [
                        'title' => 'test',
                        'id' => 'not valid'
                    ]
                ],
                null
            ],
            'relationship named type or id' => [
                [
                    'id' => '1',
                    'type' => 'articles',
                    'attributes' => [
                        'title' => 'test'
                    ],
                    'relationships' => [
                        'type' => [
                            'data' => [
                                'type' => 'people',
                                'id' => '9'
                            ]
                        ]
                    ]
                ],
                null
            ]
        ];
    }

    /**
     * @test
     */
    public function resource_object_is_valid()
    {
        $data = [
            'id' => '1',
            'type' => 'articles',
            'attributes' => [
                'title' => 'test'
            ],
            'links' => [
                'self' => '/articles/1'
            ],
            'meta' => [
                'member' => 'is valid'
            ],
            'relationships' => [
                'author' => [
                    'links' => [
                        'self' => '/articles/1/relationships/author',
                        'related' => '/articles/1/author'
                    ],
                    'data' => [
                        'type' => 'people',
                        'id' => '9'
                    ]
                ]
            ]
        ];

        JsonApiAssert::assertIsValidResourceObject($data);
    }

    /**
     * @test
     * @dataProvider isNotValidResourceObjectProvider
     */
    public function resource_object_is_not_valid($data, $failureMessage)
    {
        $fn = function ($response) {
            JsonApiAssert::assertIsValidResourceObject($response);
        };

        JsonApiAssert::assertTestFail($fn, $failureMessage, $data);
    }

    public function isNotValidResourceObjectProvider()
    {
        return [
            'not an array' => [
                'failed',
                Messages::RESOURCE_IS_NOT_ARRAY
            ],
            'id is not valid' => [
                [
                    'id' => 1,
                    'type' => 'test',
                    'attributes' => [
                        'attr' => 'value'
                    ]
                ],
                Messages::RESOURCE_ID_MEMBER_IS_NOT_STRING
            ],
            'type is not valid' => [
                [
                    'id' => '1',
                    'type' => 404,
                    'attributes' => [
                        'attr' => 'value'
                    ]
                ],
                Messages::RESOURCE_TYPE_MEMBER_IS_NOT_STRING
            ],
            'missing mandatory member' => [
                [
                    'id' => '1',
                    'type' => 'test'
                ],
                null
            ],
            'member not allowed' => [
                [
                    'id' => '1',
                    'type' => 'test',
                    'attributes' => [
                        'attr' => 'value'
                    ],
                    'wrong' => 'wrong'
                ],
                null
            ],
            'fields not valid (attribute and relationship with the same name)' => [
                [
                    'id' => '1',
                    'type' => 'articles',
                    'attributes' => [
                        'title' => 'test'
                    ],
                    'relationships' => [
                        'title' => [
                            'data' => [
                                'type' => 'people',
                                'id' => '9'
                            ]
                        ]
                    ]
                ],
                null
            ],
            'fields not valid (attribute named type or id)' => [
                [
                    'id' => '1',
                    'type' => 'articles',
                    'attributes' => [
                        'title' => 'test',
                        'id' => 'not valid'
                    ]
                ],
                null
            ],
            'fields not valid (relationship named type or id)' => [
                [
                    'id' => '1',
                    'type' => 'articles',
                    'attributes' => [
                        'title' => 'test'
                    ],
                    'relationships' => [
                        'type' => [
                            'data' => [
                                'type' => 'people',
                                'id' => '9'
                            ]
                        ]
                    ]
                ],
                null
            ]
        ];
    }
}
