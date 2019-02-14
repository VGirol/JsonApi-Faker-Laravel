<?php
namespace VGirol\JsonApi\Tests\Unit\Assert;

use VGirol\JsonApi\Tests\TestCase;
use PHPUnit\Framework\Assert as PHPUnit;
use VGirol\JsonApi\Tools\Assert\JsonApiAssertBase;
use VGirol\JsonApi\Tools\Assert\JsonApiAssertObject;
use VGirol\JsonApi\Tools\Assert\JsonApiAssertStructure;

class JsonApiIncludedTest extends TestCase
{
    use JsonApiAssertBase, JsonApiAssertObject, JsonApiAssertStructure;

    /**
     * @dataProvider validIncludedProvider
     */
    public function testValidIncluded($data)
    {
        $this->assertIsValidIncludedCollection($data['included'], $data['data']);
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
