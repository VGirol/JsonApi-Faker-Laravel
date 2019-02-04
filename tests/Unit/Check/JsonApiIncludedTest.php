<?php
namespace VGirol\JsonApi\Tests\Unit\Check;

use PHPUnit\Framework\Assert as PHPUnit;

trait JsonApiIncludedTest
{
    /**
     * @dataProvider validIncludedProvider
     */
    public function testValidIncluded($data)
    {
        $this->checkIncluded($data['included'], $data['data']);
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
}
