<?php
namespace VGirol\JsonApi\Tests\Unit\Assert;

use PHPUnit\Framework\Assert as PHPUnit;
use VGirol\JsonApi\Tests\TestCase;
use VGirol\JsonApi\Tools\Assert\JsonApiAssertBase;
use VGirol\JsonApi\Tools\Assert\JsonApiAssertMessages;
use VGirol\JsonApi\Tools\Assert\JsonApiAssertObject;
use VGirol\JsonApi\Tools\Assert\JsonApiAssertStructure;

class JsonApiPrimaryDataTest extends TestCase
{
    use JsonApiAssertBase, JsonApiAssertObject, JsonApiAssertStructure;

    /**
     * @dataProvider validDataForResourceCollectionProvider
     */
    public function testValidDataForResourceCollection($data)
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
            ]
        ];
    }

    /**
     * @dataProvider notValidDataForResourceCollectionProvider
     */
    public function testNotValidDataForResourceCollection($data, $failureMessage)
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

    /**
     * @dataProvider validPrimaryDataProvider
     */
    public function testValidPrimaryData($data)
    {
        $this->assertIsValidPrimaryData($data);
    }

    public function validPrimaryDataProvider()
    {
        return [
            'null' => [
                null
            ],
            'empty collection' => [
                []
            ],
            'resource collection' => [
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
            'unique resource' => [
                [
                    'type' => 'test',
                    'id' => '2',
                    'attributes' => [
                        'anything' => 'ok'
                    ]
                ]
            ]
        ];
    }

    /**
     * @dataProvider notValidPrimaryDataProvider
     */
    public function testNotValidPrimaryData($data, $failureMessage)
    {
        $fn = function ($data) {
            $this->assertIsValidPrimaryData($data);
        };

        $this->assertTestFail($fn, $failureMessage, $data);
    }

    public function notValidPrimaryDataProvider()
    {
        return [
            'not an array' => [
                'false',
                JsonApiAssertMessages::JSONAPI_ERROR_PRIMARY_DATA_NOT_ARRAY
            ]
        ];
    }
}
