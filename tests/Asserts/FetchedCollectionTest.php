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

        $response->assertJsonApiFetchedResourceCollection($collection, $resourceType);
    }

    private function getCollection($count = 10)
    {
        $collect = [];
        for ($i = 1; $i <= $count; $i++) {
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
     */
    public function response_pagination_links()
    {
        $status = 200;
        $headers = [
            'Content-Type' => [$this->mediaType]
        ];

        $content = [
            'links' => [
                'first' => 'url',
                'last' => 'url'
            ]
        ];
        $expected = [
            'first' => 'url',
            'last' => 'url'
        ];

        $response = Response::create(json_encode($content), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $response->assertJsonApiPaginationLinks($expected);
    }

    /**
     * @test
     * @dataProvider paginationLinksFailedProvider
     */
    public function response_pagination_links_failed($content, $expected, $failureMsg)
    {
        $fn = function ($content, $expected) {
            $status = 200;
            $headers = [
                'Content-Type' => [$this->mediaType]
            ];

            $response = Response::create(json_encode($content), $status, $headers);
            $response = TestResponse::fromBaseResponse($response);

            $response->assertJsonApiPaginationLinks($expected);
        };

        JsonApiAssert::assertTestFail($fn, $failureMsg, $content, $expected);
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
                null
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
                null
            ]
        ];
    }
}
