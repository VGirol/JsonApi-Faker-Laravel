<?php
namespace VGirol\JsonApiAssert\Laravel\Tests\Asserts;

use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\TestResponse;
use VGirol\JsonApiAssert\Laravel\Tests\TestCase;
use VGirol\JsonApiAssert\Assert as JsonApiAssert;
use VGirol\JsonApiAssert\Laravel\Tests\Tools\Models\ModelForTest;
use Illuminate\Database\Eloquent\Collection;
use VGirol\JsonApiAssert\Messages;

class FetchedRelationshipsCollectionTest extends TestCase
{
    /**
     * @test
     */
    public function response_fetched_empty_to_many_relationships()
    {
        $status = 200;
        $content = [
            'data' => []
        ];
        $headers = [
            'Content-Type' => [$this->mediaType]
        ];

        $response = Response::create(json_encode($content), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $response->assertJsonApiFetchedToManyRelationships();
    }

    /**
     * @test
     */
    public function response_fetched_to_many_relationships()
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

        $response->assertJsonApiFetchedToManyRelationships($collection, $resourceType);
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
                'id' => strval($model->getKey())
            ]);
        }

        return $data;
    }

    /**
     * @test
     * @dataProvider notValidResponseToManyRelationships
     */
    public function response_fetched_to_many_relationships_failed($status, $content, $model, $resourceType, $failureMsg)
    {
        $fn = function ($status, $content, $model, $resourceType) {
            $headers = [
                'Content-Type' => [$this->mediaType]
            ];

            $response = Response::create(json_encode($content), $status, $headers);
            $response = TestResponse::fromBaseResponse($response);

            $response->assertJsonApiFetchedToManyRelationships($model, $resourceType);
        };

        JsonApiAssert::assertTestFail($fn, $failureMsg, $status, $content, $model, $resourceType);
    }

    public function notValidResponseToManyRelationships()
    {
        $collection = $this->getCollection();
        $data = $this->getDatas($collection);
        $model = $collection->first();
        $resourceType = $model->getResourceType();

        return [
            'bad status' => [
                400,
                [
                    'data' => $data
                ],
                $collection,
                $resourceType,
                'Expected status code 200 but received 400.'
            ],
            'no data member' => [
                200,
                [
                    'errors' => [
                        [
                            'status' => '400'
                        ]
                    ]
                ],
                $collection,
                $resourceType,
                sprintf(Messages::HAS_MEMBER, 'data')
            ],
            'not an array of objects' => [
                200,
                [
                    'data' => [
                        'type' => $resourceType,
                        'id' => '10'
                    ]
                ],
                $collection,
                $resourceType,
                Messages::MUST_BE_ARRAY_OF_OBJECTS
            ],
            'not valid collection' => [
                200,
                [
                    'data' => [
                        $data[0]
                    ]
                ],
                $collection,
                $resourceType,
                null
            ]
        ];
    }

    /**
     * @test
     */
    public function response_relationships_links()
    {
        $status = 200;
        $headers = [
            'Content-Type' => [$this->mediaType]
        ];

        $content = [
            'data' => [
                'relationships' => [
                    'test' => [
                        'links' => [
                            'self' => 'url',
                            'related' => 'url'
                        ]
                    ]
                ]
            ]
        ];
        $expected = [
            'self' => 'url',
            'related' => 'url'
        ];
        $path = 'data.relationships.test';

        $response = Response::create(json_encode($content), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $response->assertJsonApiRelationshipsLinks($expected, $path);
    }

    /**
     * @test
     * @dataProvider relationshipsLinksFailedProvider
     */
    public function response_relationships_links_failed($content, $expected, $path, $failureMsg)
    {
        $fn = function ($content, $expected, $path) {
            $status = 200;
            $headers = [
                'Content-Type' => [$this->mediaType]
            ];

            $response = Response::create(json_encode($content), $status, $headers);
            $response = TestResponse::fromBaseResponse($response);

            $response->assertJsonApiRelationshipsLinks($expected, $path);
        };

        JsonApiAssert::assertTestFail($fn, $failureMsg, $content, $expected, $path);
    }

    public function relationshipsLinksFailedProvider()
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
                'meta',
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
            ]
        ];
    }
}
