<?php
namespace VGirol\JsonApi\Tests\Unit\Assert;

use VGirol\JsonApiAssert\JsonApiAssert;
use PHPUnit\Framework\Assert as PHPUnit;
use VGirol\JsonApiAssert\Tests\TestCase;

class JsonApiIncludedTest extends TestCase
{
    use JsonApiAssert;

    /**
     * @note
     * @test
     * @dataProvider validIncludedProvider
     */
    public function compound_document_is_valid($json)
    {
        $this->assertIsValidIncludedCollection($json['included'], $json['data']);
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
     * @note
     * @test
     * @dataProvider notValidIncludedProvider
     */
    public function compound_document_is_not_valid($json, $failureMessage)
    {
        $fn = function ($json) {
            $this->assertIsValidIncludedCollection($json['included'], $json['data']);
        };

        $this->assertTestFail($fn, $failureMessage, $json);
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
