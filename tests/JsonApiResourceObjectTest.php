<?php
namespace VGirol\JsonApiAssert\Tests;

use VGirol\JsonApiAssert\JsonApiAssert;
use PHPUnit\Framework\Assert as PHPUnit;
use VGirol\JsonApiAssert\Tests\TestCase;
use VGirol\JsonApiAssert\JsonApiAssertMessages;

class JsonApiResourceObjectTest extends TestCase
{
    use JsonApiAssert;

    /**
     * @note
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

        $this->assertResourceObjectHasValidTopLevelStructure($data);
    }

    /**
     * @note
     * @test
     * @dataProvider hasotValidTopLevelStructureProvider
     */
    public function resource_has_not_valid_top_level_structure($data, $failureMessage)
    {
        $fn = function ($response) {
            $this->assertResourceObjectHasValidTopLevelStructure($response);
        };

        $this->assertTestFail($fn, $failureMessage, $data);
    }

    public function hasotValidTopLevelStructureProvider()
    {
        return [
            'not an array' => [
                'failed',
                JsonApiAssertMessages::JSONAPI_ERROR_RESOURCE_IS_NOT_ARRAY
            ],
            'id is missing' => [
                [
                    'type' => 'test',
                    'attributes' => [
                        'attr' => 'value'
                    ]
                ],
                JsonApiAssertMessages::JSONAPI_ERROR_RESOURCE_ID_MEMBER_IS_ABSENT
            ],
            'type is missing' => [
                [
                    'id' => '1',
                    'attributes' => [
                        'attr' => 'value'
                    ]
                ],
                JsonApiAssertMessages::JSONAPI_ERROR_RESOURCE_TYPE_MEMBER_IS_ABSENT
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
     * @note
     * @test
     */
    public function resource_id_member_is_valid()
    {
        $data = [
            'id' => '1',
            'type' => 'test'
        ];

        $this->assertResourceIdMember($data);
    }

    /**
     * @note
     * @test
     * @dataProvider notValidResourceIdMemberProvider
     */
    public function resource_id_member_is_not_valid($data, $failureMessage)
    {
        $fn = function ($response) {
            $this->assertResourceIdMember($response);
        };

        $this->assertTestFail($fn, $failureMessage, $data);
    }

    public function notValidResourceIdMemberProvider()
    {
        return [
            'id is empty' => [
                [
                    'id' => '',
                    'type' => 'test'
                ],
                JsonApiAssertMessages::JSONAPI_ERROR_RESOURCE_ID_MEMBER_IS_EMPTY
            ],
            'id is not a string' => [
                [
                    'id' => 1,
                    'type' => 'test'
                ],
                JsonApiAssertMessages::JSONAPI_ERROR_RESOURCE_ID_MEMBER_IS_NOT_STRING
            ]
        ];
    }

    /**
     * @note
     * @test
     */
    public function resource_type_member_is_valid()
    {
        $data = [
            'id' => '1',
            'type' => 'test'
        ];

        $this->assertResourceTypeMember($data);
    }

    /**
     * @note
     * @test
     * @dataProvider notValidResourceTypeMemberProvider
     */
    public function resource_type_member_is_not_valid($data, $failureMessage)
    {
        $fn = function ($response) {
            $this->assertResourceTypeMember($response);
        };

        $this->assertTestFail($fn, $failureMessage, $data);
    }

    public function notValidResourceTypeMemberProvider()
    {
        return [
            'type is empty' => [
                [
                    'id' => '1',
                    'type' => ''
                ],
                JsonApiAssertMessages::JSONAPI_ERROR_RESOURCE_TYPE_MEMBER_IS_EMPTY
            ],
            'type is not a string' => [
                [
                    'id' => '1',
                    'type' => 404
                ],
                JsonApiAssertMessages::JSONAPI_ERROR_RESOURCE_TYPE_MEMBER_IS_NOT_STRING
            ],
            'type value has forbidden characters' => [
                [
                    'id' => '1',
                    'type' => 'test+1'
                ],
                JsonApiAssertMessages::JSONAPI_ERROR_MEMBER_NAME_HAVE_RESERVED_CHARACTERS
            ]
        ];
    }

    /**
     * @note
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

        $this->assertIsValidResourceIdentifierObject($data);
    }

    /**
     * @note
     * @test
     * @dataProvider isNotValidResourceIdentifierObjectProvider
     */
    public function resource_identifier_object_is_not_valid($data, $failureMessage)
    {
        $fn = function ($response) {
            $this->assertIsValidResourceIdentifierObject($response);
        };

        $this->assertTestFail($fn, $failureMessage, $data);
    }

    public function isNotValidResourceIdentifierObjectProvider()
    {
        return [
            'not an array' => [
                'failed',
                JsonApiAssertMessages::JSONAPI_ERROR_RESOURCE_IDENTIFIER_IS_NOT_ARRAY
            ],
            'id is missing' => [
                [
                    'type' => 'test'
                ],
                JsonApiAssertMessages::JSONAPI_ERROR_RESOURCE_ID_MEMBER_IS_ABSENT
            ],
            'id is not valid' => [
                [
                    'id' => 1,
                    'type' => 'test'
                ],
                JsonApiAssertMessages::JSONAPI_ERROR_RESOURCE_ID_MEMBER_IS_NOT_STRING
            ],
            'type is missing' => [
                [
                    'id' => '1'
                ],
                JsonApiAssertMessages::JSONAPI_ERROR_RESOURCE_TYPE_MEMBER_IS_ABSENT
            ],
            'type is not valid' => [
                [
                    'id' => '1',
                    'type' => 404
                ],
                JsonApiAssertMessages::JSONAPI_ERROR_RESOURCE_TYPE_MEMBER_IS_NOT_STRING
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
     * @note
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

        $this->assertValidFields($data);
    }

    /**
     * @note
     * @test
     * @dataProvider isNotValidResourceFieldProvider
     */
    public function resource_field_is_not_valid($data, $failureMessage)
    {
        $fn = function ($response) {
            $this->assertValidFields($response);
        };

        $this->assertTestFail($fn, $failureMessage, $data);
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
     * @note
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

        $this->assertIsValidResourceObject($data);
    }

    /**
     * @note
     * @test
     * @dataProvider isNotValidResourceObjectProvider
     */
    public function resource_object_is_not_valid($data, $failureMessage)
    {
        $fn = function ($response) {
            $this->assertIsValidResourceObject($response);
        };

        $this->assertTestFail($fn, $failureMessage, $data);
    }

    public function isNotValidResourceObjectProvider()
    {
        return [
            'not an array' => [
                'failed',
                JsonApiAssertMessages::JSONAPI_ERROR_RESOURCE_IS_NOT_ARRAY
            ],
            'id is not valid' => [
                [
                    'id' => 1,
                    'type' => 'test',
                    'attributes' => [
                        'attr' => 'value'
                    ]
                ],
                JsonApiAssertMessages::JSONAPI_ERROR_RESOURCE_ID_MEMBER_IS_NOT_STRING
            ],
            'type is not valid' => [
                [
                    'id' => '1',
                    'type' => 404,
                    'attributes' => [
                        'attr' => 'value'
                    ]
                ],
                JsonApiAssertMessages::JSONAPI_ERROR_RESOURCE_TYPE_MEMBER_IS_NOT_STRING
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
