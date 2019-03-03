<?php
namespace VGirol\JsonApiAssert\Laravel\Tests\Asserts;

use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\TestResponse;
use VGirol\JsonApiAssert\Laravel\Tests\TestCase;
use VGirol\JsonApiAssert\Assert as JsonApiAssert;
use VGirol\JsonApiAssert\Laravel\Tests\Tools\Models\ModelForTest;
use Illuminate\Database\Eloquent\Collection;
use VGirol\JsonApiAssert\Messages;

class FetchedCollectionTest extends TestCase
{
    /**
     * @test
     */
    public function response_fetched_resource_collection()
    {
        $collection = $this->getCollection();
        $data = $this->getDatas($collection);
        $model = $collection->first();

        $resourceType = $model->getResourceType();
        $status = 200;
        $content = [
            'data' => $data
        ];
        $headers = [
            'Content-Type' => [$this->mediaType]
        ];

        $response = Response::create(json_encode($content), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $options = [
            'colCount' => count($data),
            'resourceType' => $resourceType,
            'itemPerPage' => 20
        ];
        $response->assertJsonApiFetchedResourceCollection($collection, $options);
    }

    private function getCollection()
    {
        $collect = [];
        for ($i = 1; $i <= 10; $i++) {
            $model = new ModelForTest([
                'TST_ID' => $i,
                'TST_NAME' => "test_{$i}",
                'TST_NUMBER' => $i,
                'TST_CREATION_DATE' => '01-01-1970'
            ]);
            array_push($collect, $model);
        }

        return new Collection($collect);
    }

    private function getDatas($collection)
    {
        $data = [];
        foreach ($collection as $model) {
            array_push($data, [
                'type' => $model->getResourceType(),
                'id' => strval($model->getKey()),
                'attributes' => $model->toArray()
            ]);
        }

        return $data;
    }

    /**
     * @test
     * @dataProvider paginationLinksProvider
     */
    public function response_pagination_links($content, $expected, $path)
    {
        $status = 200;
        $headers = [
            'Content-Type' => [$this->mediaType]
        ];

        $response = Response::create(json_encode($content), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $response->assertJsonApiPaginationLinks($expected, $path);
    }

    public function paginationLinksProvider()
    {
        return [
            'top level links member' => [
                [
                    'links' => [
                        'first' => 'url',
                        'last' => 'url'
                    ]
                ],
                [
                    'first' => 'url',
                    'last' => 'url'
                ],
                null
            ],
            'included member' => [
                [
                    'included' => [
                        'links' => [
                            'first' => 'url',
                            'last' => 'url'
                        ]
                    ]
                ],
                [
                    'first' => 'url',
                    'last' => 'url'
                ],
                'included'
            ],
            'anywhere member' => [
                [
                    'meta' => [
                        'test' => [
                            'faraway' => [
                                'links' => [
                                    'first' => 'url',
                                    'last' => 'url'
                                ]
                            ]
                        ]
                    ]
                ],
                [
                    'first' => 'url',
                    'last' => 'url'
                ],
                'meta.test.faraway'
            ]
        ];
    }

    /**
     * @test
     * @dataProvider paginationLinksFailedProvider
     */
    public function response_pagination_links_failed($content, $expected, $path, $failureMsg)
    {
        $fn = function ($content, $expected, $path) {
            $status = 200;
            $headers = [
                'Content-Type' => [$this->mediaType]
            ];

            $response = Response::create(json_encode($content), $status, $headers);
            $response = TestResponse::fromBaseResponse($response);

            $response->assertJsonApiPaginationLinks($expected, $path);
        };

        JsonApiAssert::assertTestFail($fn, $failureMsg, $content, $expected, $path);
    }

    public function paginationLinksFailedProvider()
    {
        return [
            'no links member' => [
                [
                    'meta' => [
                        'bad' => 'content'
                    ]
                ],
                [
                    'first' => 'url',
                    'last' => 'url'
                ],
                null,
                sprintf(Messages::HAS_MEMBER, 'links')
            ],
            'bad links member' => [
                [
                    'links' => [
                        'first' => 'url',
                        'bad' => 'url'
                    ]
                ],
                [
                    'first' => 'url',
                    'last' => 'url'
                ],
                null,
                Messages::ONLY_ALLOWED_MEMBERS
            ],
            'no match' => [
                [
                    'links' => [
                        'first' => 'url',
                        'last' => 'url2'
                    ]
                ],
                [
                    'first' => 'url',
                    'last' => 'url'
                ],
                null,
                null
            ],
            'bad path' => [
                [
                    'included' => [
                        'links' => [
                            'first' => 'url',
                            'last' => 'url'
                        ]
                    ]
                ],
                [
                    'first' => 'url',
                    'last' => 'url'
                ],
                'included.bad',
                sprintf(Messages::HAS_MEMBER, 'bad')
            ]
        ];
    }
}
