<?php
namespace VGirol\JsonApi\Tests\Unit\Check;

use PHPUnit\Framework\Assert as PHPUnit;

trait JsonApiResourceObjectTest
{
    public function testValidResourceIdMember()
    {
        $data = [
            'id' => '1',
            'type' => 'test'
        ];

        $this->assertResourceHasIdMember($data);
    }

    /**
     * @depends testValidResourceIdMember
     * @dataProvider notValidResourceIdMemberProvider
     */
    public function testNotValidResourceIdMember($data, $failureMessage)
    {
        $fn = function ($response) {
            $this->assertResourceHasIdMember($response);
        };

        $this->assertTestFail($fn, $failureMessage, $data);
    }

    public function notValidResourceIdMemberProvider()
    {
        return [
            'no id member' => [
                [
                    'type' => 'test'
                ],
                static::$JSONAPI_ERROR_RESOURCE_ID_MEMBER_IS_ABSENT
            ],
            'id is empty' => [
                [
                    'id' => '',
                    'type' => 'test'
                ],
                static::$JSONAPI_ERROR_RESOURCE_ID_MEMBER_IS_EMPTY
            ],
            'id is not a string' => [
                [
                    'id' => 1,
                    'type' => 'test'
                ],
                static::$JSONAPI_ERROR_RESOURCE_ID_MEMBER_IS_NOT_STRING
            ]
        ];
    }

    /**
     * @depends testValidMemberName
     * @depends testNotValidMemberName
     */
    public function testValidResourceTypeMember()
    {
        $data = [
            'id' => '1',
            'type' => 'test'
        ];

        $this->assertResourceHasTypeMember($data);
    }

    /**
     * @depends testValidResourceTypeMember
     * @dataProvider notValidResourceTypeMemberProvider
     */
    public function testNotValidResourceTypeMember($data, $failureMessage)
    {
        $fn = function ($response) {
            $this->assertResourceHasTypeMember($response);
        };

        $this->assertTestFail($fn, $failureMessage, $data);
    }

    public function notValidResourceTypeMemberProvider()
    {
        return [
            'no type member' => [
                [
                    'id' => '1'
                ],
                static::$JSONAPI_ERROR_RESOURCE_TYPE_MEMBER_IS_ABSENT
            ],
            'type is empty' => [
                [
                    'id' => '1',
                    'type' => ''
                ],
                static::$JSONAPI_ERROR_RESOURCE_TYPE_MEMBER_IS_EMPTY
            ],
            'type is not a string' => [
                [
                    'id' => '1',
                    'type' => 404
                ],
                static::$JSONAPI_ERROR_RESOURCE_TYPE_MEMBER_IS_NOT_STRING
            ],
            'type value has forbidden characters' => [
                [
                    'id' => '1',
                    'type' => 'test+1'
                ],
                null
            ]
        ];
    }

    public function testIsValidResourceIdentifierObject()
    {
        $data = [
            'id' => '1',
            'type' => 'test'
        ];

        $this->assertIsResourceIdentifierObject($data);
    }

    /**
     * @dataProvider isNotValidResourceIdentifierObjectProvider
     */
    public function testIsNotValidResourceIdentifierObject($data, $failureMessage)
    {
        $fn = function ($response) {
            $this->assertIsResourceIdentifierObject($response);
        };

        $this->assertTestFail($fn, $failureMessage, $data);
    }

    public function isNotValidResourceIdentifierObjectProvider()
    {
        return [
            'not an array' => [
                'failed',
                static::$JSONAPI_ERROR_RESOURCE_IDENTIFIER_IS_NOT_ARRAY
            ],
            'id is not valid' => [
                [
                    'id' => 1,
                    'type' => 'test'
                ],
                static::$JSONAPI_ERROR_RESOURCE_ID_MEMBER_IS_NOT_STRING
            ],
            'type is not valid' => [
                [
                    'id' => '1',
                    'type' => 404
                ],
                static::$JSONAPI_ERROR_RESOURCE_TYPE_MEMBER_IS_NOT_STRING
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

    public function testIsValidResourceObject()
    {
        $data = [
            'id' => '1',
            'type' => 'test',
            'attributes' => [
                'attr' => 'value'
            ]
        ];

        $this->assertIsResourceObject($data);
    }

    /**
     * @dataProvider isNotValidResourceObjectProvider
     */
    public function testIsNotValidResourceObject($data, $failureMessage)
    {
        $fn = function ($response) {
            $this->assertIsResourceObject($response);
        };

        $this->assertTestFail($fn, $failureMessage, $data);
    }

    public function isNotValidResourceObjectProvider()
    {
        return [
            'not an array' => [
                'failed',
                static::$JSONAPI_ERROR_RESOURCE_IS_NOT_ARRAY
            ],
            'id is not valid' => [
                [
                    'id' => 1,
                    'type' => 'test',
                    'attributes' => [
                        'attr' => 'value'
                    ]
                ],
                static::$JSONAPI_ERROR_RESOURCE_ID_MEMBER_IS_NOT_STRING
            ],
            'type is not valid' => [
                [
                    'id' => '1',
                    'type' => 404,
                    'attributes' => [
                        'attr' => 'value'
                    ]
                ],
                static::$JSONAPI_ERROR_RESOURCE_TYPE_MEMBER_IS_NOT_STRING
            ],
            'miss mandatory member' => [
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
            ]
        ];
    }

    public function testValidResourceIdentifierObject()
    {
        $data = [
            'id' => '1',
            'type' => 'test',
            'meta' => [
                'anything' => 'valid'
            ]
        ];

        $this->checkResourceIdentifierObject($data);
    }

    public function testNotValidResourceIdentifierObject()
    {
        $data = [
            'id' => '1',
            'type' => 'test',
            'meta' => 'not valid'
        ];

        $fn = function ($data) {
            $this->checkResourceIdentifierObject($data);
        };

        $this->assertTestFail($fn, null, $data);
    }

    public function testValidResourceObject()
    {
        $data = [
            'id' => '1',
            'type' => 'test',
            'attributes' => [
                'attr' => 'value'
            ],
            'links' => [
                'self' => 'url'
            ],
            'relationships' => [
                'test' => [
                    'data' => [
                        'type' => 'anything',
                        'id' => '12'
                    ]
                ]
            ],
            'meta' => [
                'anything' => 'valid'
            ]
        ];

        $this->checkResourceObject($data);
    }

    public function testNotValidResourceObject()
    {
        $data = [
            'id' => '1',
            'type' => 'test',
            'attributes' => [
                'anything' => 'valid'
            ],
            'meta' => 'not valid'
        ];

        $fn = function ($data) {
            $this->checkResourceObject($data);
        };

        $this->assertTestFail($fn, null, $data);
    }
}
