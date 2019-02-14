<?php
namespace VGirol\JsonApi\Tests\Unit\Assert;

use PHPUnit\Framework\Assert as PHPUnit;
use VGirol\JsonApi\Tests\TestCase;
use VGirol\JsonApi\Tools\Assert\JsonApiAssertBase;
use VGirol\JsonApi\Tools\Assert\JsonApiAssertMessages;
use VGirol\JsonApi\Tools\Assert\JsonApiAssertObject;
use VGirol\JsonApi\Tools\Assert\JsonApiAssertStructure;

class JsonApiTopLevelMembersTest extends TestCase
{
    use JsonApiAssertBase, JsonApiAssertObject, JsonApiAssertStructure;

    public function testValidTopLevelMembers()
    {
        $data = [
            'links' => [
                'self' => 'http://example.com/articles'
            ],
            'data' => [
                [
                    'type' => 'articles',
                    'id' => '1',
                    'attributes' => [
                        'title' => 'JSON:API paints my bikeshed!'
                    ]
                ],
                [
                    'type' => 'articles',
                    'id' => '2',
                    'attributes' => [
                        'title' => 'Rails is Omakase'
                    ]
                ]
            ]
        ];

        $this->assertHasValidTopLevelMembers($data);
    }

    /**
     * @dataProvider notValidTopLevelMembersProvider
     */
    public function testNotValidTopLevelMembers($data, $failureMessage)
    {
        $fn = function ($data) {
            $this->assertHasValidTopLevelMembers($data);
        };

        $this->assertTestFail($fn, $failureMessage, $data);
    }

    public function notValidTopLevelMembersProvider()
    {
        return [
            'miss mandatory members' => [
                [
                    'links' => [
                        'self' => 'http://example.com/articles'
                    ]
                ],
                null
            ],
            'data and error incompatible' => [
                [
                    'errors' => [
                        [
                            'code' => 'E13'
                        ]
                    ],
                    'data' => [
                        'type' => 'articles',
                        'id' => '1',
                        'attributes' => [
                            'title' => 'JSON:API paints my bikeshed!'
                        ]
                    ]
                ],
                JsonApiAssertMessages::JSONAPI_ERROR_TOP_LEVEL_DATA_AND_ERROR
            ],
            'only allowed members' => [
                [
                    'data' => [
                        'type' => 'articles',
                        'id' => '1',
                        'attributes' => [
                            'title' => 'JSON:API paints my bikeshed!'
                        ]
                    ],
                    'anything' => 'not allowed'
                ],
                JsonApiAssertMessages::JSONAPI_ERROR_ONLY_ALLOWED_MEMBERS
            ],
            'no data but included' => [
                [
                    'included' => 'not allowed',
                    'meta' => [
                        'anything' => 'ok'
                    ]
                ],
                JsonApiAssertMessages::JSONAPI_ERROR_TOP_LEVEL_DATA_AND_INCLUDED
            ]
        ];
    }
}
