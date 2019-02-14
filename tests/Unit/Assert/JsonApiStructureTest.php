<?php
namespace VGirol\JsonApi\Tests\Unit\Assert;

use PHPUnit\Framework\Assert as PHPUnit;
use VGirol\JsonApi\Tests\TestCase;
use VGirol\JsonApi\Tools\Assert\JsonApiAssertBase;
use VGirol\JsonApi\Tools\Assert\JsonApiAssertObject;
use VGirol\JsonApi\Tools\Assert\JsonApiAssertStructure;

class JsonApiStructureTest extends TestCase
{
    use JsonApiAssertBase, JsonApiAssertObject, JsonApiAssertStructure;

    /**
     * @dataProvider validStructureProvider
     */
    public function testValidStructure($data)
    {
        $this->assertHasValidStructure($data);
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
}
