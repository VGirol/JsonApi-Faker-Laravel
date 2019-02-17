<?php
namespace VGirol\JsonApiAssert\Tests;

use VGirol\JsonApiAssert\JsonApiAssert;
use PHPUnit\Framework\Assert as PHPUnit;
use VGirol\JsonApiAssert\Tests\TestCase;
use VGirol\JsonApiAssert\JsonApiAssertMessages;

class JsonApiPrimaryDataTest extends TestCase
{
    use JsonApiAssert;

    /**
     * @note
     * @test
     * @dataProvider validDataForSingleResourceProvider
     */
    public function single_resource_is_valid($data)
    {
        $this->assertIsValidSingleResource($data, true);
    }

    public function validDataForSingleResourceProvider()
    {
        return [
            'resource identifier object' => [
                [
                    'type' => 'test',
                    'id' => '2'
                ]
            ],
            'resource object' => [
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
     * @note
     * @test
     * @dataProvider notValidDataForSingleResourceProvider
     */
    public function single_resource_is_not_valid($data, $failureMessage)
    {
        $fn = function ($data) {
            $this->assertIsValidSingleResource($data);
        };

        $this->assertTestFail($fn, $failureMessage, $data);
    }

    public function notValidDataForSingleResourceProvider()
    {
        return [
            'resource identifier not valid' => [
                [
                    'id' => 666,
                    'type' => 'test'
                ],
                JsonApiAssertMessages::JSONAPI_ERROR_RESOURCE_ID_MEMBER_IS_NOT_STRING
            ],
            'resource object not valid' => [
                [
                    'type' => 'test',
                    'id' => '2',
                    'attributes' => [
                        'anything' => 'ok',
                        '+not valid' => 'error'
                    ]
                ],
                null
            ]
        ];
    }

    /**
     * @note
     * @test
     * @dataProvider validPrimaryDataProvider
     */
    public function primary_data_is_valid($data)
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
     * @note
     * @test
     * @dataProvider notValidPrimaryDataProvider
     */
    public function primary_data_is_not_valid($data, $failureMessage)
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
                'bad',
                null
            ],
            'not valid resource collection' => [
                [
                    [
                        'type' => 'test',
                        'id' => '1'
                    ],
                    [
                        'type' => 'test',
                        'id' => '2',
                        'attributes' => [
                            'anything' => 'valid'
                        ]
                    ]
                ],
                null
            ]
        ];
    }
}
