<?php
namespace VGirol\JsonApiAssert\Tests;

use VGirol\JsonApiAssert\JsonApiAssert;
use VGirol\JsonApiAssert\Tests\TestCase;
use VGirol\JsonApiAssert\JsonApiAssertMessages;

class JsonApiResourceCollectionTest extends TestCase
{
    use JsonApiAssert;

    /**
     * @note
     * @test
     * @dataProvider validDataForResourceCollectionProvider
     */
    public function resource_collection_is_valid($data)
    {
        $this->assertIsValidResourceCollection($data, true);
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
     * @note
     * @test
     * @dataProvider notValidDataForResourceCollectionProvider
     */
    public function resource_collection_is_not_valid($data, $failureMessage)
    {
        $fn = function ($data) {
            $this->assertIsValidResourceCollection($data, true);
        };

        $this->assertTestFail($fn, $failureMessage, $data);
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
                JsonApiAssertMessages::JSONAPI_ERROR_PRIMARY_DATA_SAME_TYPE
            ]
        ];
    }
}
