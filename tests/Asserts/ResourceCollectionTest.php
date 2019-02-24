<?php
namespace VGirol\JsonApiAssert\Tests\Asserts;

use VGirol\JsonApiAssert\Assert as JsonApiAssert;
use VGirol\JsonApiAssert\Tests\TestCase;
use VGirol\JsonApiAssert\Messages;

class ResourceCollectionTest extends TestCase
{
    /**
     * @test
     * @dataProvider validDataForResourceCollectionProvider
     */
    public function resource_collection_is_valid($data)
    {
        JsonApiAssert::assertIsValidResourceCollection($data, true);
    }

    public function validDataForResourceCollectionProvider()
    {
        return [
            'resource identifier objects' => [
                [
                    [
                        'type' => 'test',
                        'id' => '2'
                    ],
                    [
                        'type' => 'test',
                        'id' => '3'
                    ]
                ]
            ],
            'resource objects' => [
                [
                    [
                        'type' => 'test',
                        'id' => '2',
                        'attributes' => [
                            'anything' => 'ok'
                        ]
                    ],
                    [
                        'type' => 'test',
                        'id' => '3',
                        'attributes' => [
                            'anything' => 'ok'
                        ]
                    ]
                ]
            ],
            'only one item' => [
                [
                    [
                        'type' => 'test',
                        'id' => '2'
                    ]
                ]
            ],
            'empty collection' => [
                []
            ],
        ];
    }

    /**
     * @test
     * @dataProvider notValidDataForResourceCollectionProvider
     */
    public function resource_collection_is_not_valid($data, $failureMessage)
    {
        $fn = function ($data) {
            JsonApiAssert::assertIsValidResourceCollection($data, true);
        };

        JsonApiAssert::assertTestFail($fn, $failureMessage, $data);
    }

    public function notValidDataForResourceCollectionProvider()
    {
        return [
            'not an array of objects' => [
                [
                    'anything' => 'false'
                ],
                null
            ],
            'not all objects are of same type' => [
                [
                    [
                        'type' => 'test',
                        'id' => '2',
                        'attributes' => [
                            'anything' => 'ok'
                        ]
                    ],
                    [
                        'type' => 'test',
                        'id' => '3'
                    ]
                ],
                Messages::PRIMARY_DATA_SAME_TYPE
            ]
        ];
    }
}
