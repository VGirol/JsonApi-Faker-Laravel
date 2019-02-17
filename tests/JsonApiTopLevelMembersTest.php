<?php
namespace VGirol\JsonApiAssert\Tests;

use VGirol\JsonApiAssert\JsonApiAssert;
use PHPUnit\Framework\Assert as PHPUnit;
use VGirol\JsonApiAssert\Tests\TestCase;
use VGirol\JsonApiAssert\JsonApiAssertMessages;

class JsonApiTopLevelMembersTest extends TestCase
{
    use JsonApiAssert;

    /**
     * @note
     * @test
     */
    public function document_has_valid_top_level_members()
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
                        'title' => 'First'
                    ]
                ],
                [
                    'type' => 'articles',
                    'id' => '2',
                    'attributes' => [
                        'title' => 'Second'
                    ]
                ]
            ]
        ];

        $this->assertHasValidTopLevelMembers($data);
    }

    /**
     * @note
     * @test
     * @dataProvider notValidTopLevelMembersProvider
     */
    public function document_has_not_valid_top_level_members($data, $failureMessage)
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
