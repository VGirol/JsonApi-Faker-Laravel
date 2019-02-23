<?php
namespace VGirol\JsonApiAssert\Tests\Asserts;

use VGirol\JsonApiAssert\Assert as JsonApiAssert;
use VGirol\JsonApiAssert\Tests\TestCase;

class IncludedTest extends TestCase
{
    /**
     * @test
     * @dataProvider validIncludedProvider
     */
    public function compound_document_is_valid($json)
    {
        JsonApiAssert::assertIsValidIncludedCollection($json['included'], $json['data']);
    }

    public function validIncludedProvider()
    {
        return [
            'with data' => [
                [
                    'data' => [
                        [
                            'type' => 'articles',
                            'id' => '1',
                            'relationships' => [
                                'test' => [
                                    'data' => [
                                        'type' => 'first',
                                        'id' => '10'
                                    ]
                                ]
                            ]
                        ],
                        [
                            'type' => 'articles',
                            'id' => '2',
                            'attributes' => [
                                'title' => 'Rails is Omakase'
                            ]
                        ]
                    ],
                    'included' => [
                        [
                            'type' => 'first',
                            'id' => '10',
                            'relationships' => [
                                'test' => [
                                    'data' => [
                                        'type' => 'second',
                                        'id' => '12'
                                    ]
                                ]
                            ]
                        ],
                        [
                            'type' => 'second',
                            'id' => '12'
                        ]
                    ]
                ]
            ]
        ];
    }

    /**
     * @test
     * @dataProvider notValidIncludedProvider
     */
    public function compound_document_is_not_valid($json, $failureMessage)
    {
        $fn = function ($json) {
            JsonApiAssert::assertIsValidIncludedCollection($json['included'], $json['data']);
        };

        JsonApiAssert::assertTestFail($fn, $failureMessage, $json);
    }

    public function notValidIncludedProvider()
    {
        return [
            'included member is not an array' => [
                [
                    'data' => [],
                    'included' => 'bad'
                ],
                null
            ],
            'included member is not a resource collection' => [
                [
                    'data' => [],
                    'included' => [
                        'id' => '1',
                        'type' => 'test'
                    ]
                ],
                null
            ],
            'one included resource is not identified by a resource identifier object' => [
                [
                    'data' => [
                        'type' => 'articles',
                        'id' => '1',
                        'relationships' => [
                            'test' => [
                                'data' => [
                                    'type' => 'first',
                                    'id' => '10'
                                ]
                            ]
                        ]
                    ],
                    'included' => [
                        [
                            'type' => 'first',
                            'id' => '10'
                        ],
                        [
                            'type' => 'second',
                            'id' => '12'
                        ]
                    ]
                ],
                null
            ],
            'a resource is included twice' => [
                [
                    'data' => [
                        'type' => 'articles',
                        'id' => '1',
                        'relationships' => [
                            'test' => [
                                'data' => [
                                    'type' => 'first',
                                    'id' => '10'
                                ]
                            ]
                        ]
                    ],
                    'included' => [
                        [
                            'type' => 'first',
                            'id' => '10'
                        ],
                        [
                            'type' => 'first',
                            'id' => '10'
                        ]
                    ]
                ],
                null
            ]
        ];
    }
}
