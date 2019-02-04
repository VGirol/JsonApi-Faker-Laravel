<?php
namespace VGirol\JsonApi\Tests\Unit\Check;

use PHPUnit\Framework\Assert as PHPUnit;

trait JsonApiPrimaryDataTest
{
    /**
     * @dataProvider validDataForResourceCollectionProvider
     */
    public function testValidDataForResourceCollection($data)
    {
        $this->checkDataForResourceCollection($data, true);
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
            $this->checkDataForResourceCollection($data, true);
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
                static::$JSONAPI_ERROR_PRIMARY_DATA_SAME_TYPE
            ]
        ];
    }

    /**
     * @dataProvider validPrimaryDataProvider
     */
    public function testValidPrimaryData($data)
    {
        $this->checkPrimaryData($data);
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
            $this->checkPrimaryData($data);
        };

        $this->assertTestFail($fn, $failureMessage, $data);
    }

    public function notValidPrimaryDataProvider()
    {
        return [
            'not an array' => [
                'false',
                static::$JSONAPI_ERROR_PRIMARY_DATA_NOT_ARRAY
            ]
        ];
    }
}
