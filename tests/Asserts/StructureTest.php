<?php
namespace VGirol\JsonApiAssert\Tests\Asserts;

use VGirol\JsonApiAssert\Assert as JsonApiAssert;
use VGirol\JsonApiAssert\Tests\TestCase;

class StructureTest extends TestCase
{
    /**
     * @test
     * @dataProvider validStructureProvider
     */
    public function document_has_valid_structure($data)
    {
        JsonApiAssert::assertHasValidStructure($data);
    }

    public function validStructureProvider()
    {
        return [
            'with data' => [
                [
                    'links' => [
                        'self' => 'http://example.com/articles',
                        'first' => 'url',
                        'last' => 'url'
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
                            ],
                            'relationships' => [
                                'test' => [
                                    'data' => [
                                        'type' => 'relation',
                                        'id' => '12'
                                    ]
                                ]
                            ]
                        ]
                    ],
                    'meta' => [
                        'anything' => 'valid'
                    ],
                    'included' => [
                        [
                            'type' => 'relation',
                            'id' => '12',
                            'attributes' => [
                                'anything' => 'valid'
                            ]
                        ]
                    ]
                ]
            ],
            'with errors' => [
                [
                    'errors' => [
                        [
                            'code' => 'E13'
                        ],
                        [
                            'code' => 'E14'
                        ]
                    ],
                    'jsonapi' => [
                        'version' => 'valid'
                    ]
                ]
            ]
        ];
    }

    /**
     * @test
     * @dataProvider notValidStructureProvider
     */
    public function document_has_not_valid_structure($data, $failureMessage)
    {
        $fn = function ($data) {
            JsonApiAssert::assertHasValidStructure($data);
        };

        JsonApiAssert::assertTestFail($fn, $failureMessage, $data);
    }

    public function notValidStructureProvider()
    {
        return [
            'bad value for top-level links member' => [
                [
                    'links' => [
                        'self' => 'http://example.com/articles',
                        'forbidden' => 'not valid'
                    ],
                    'data' => [
                        'type' => 'articles',
                        'id' => '1',
                        'attributes' => [
                            'title' => 'First'
                        ]
                    ]
                ],
                null
            ]
        ];
    }
}
