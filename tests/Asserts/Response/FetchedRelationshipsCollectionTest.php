<?php
namespace VGirol\JsonApiAssert\Laravel\Tests\Asserts\Response;

use Illuminate\Support\Collection;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Http\Response;
use VGirol\JsonApiAssert\Laravel\Tests\TestCase;
use VGirol\JsonApiAssert\Messages;

class FetchedRelationshipsCollectionTest extends TestCase
{
    /**
     * @test
     */
    public function responseFetchedEmptyToManyRelationships()
    {
        $status = 200;
        $content = [
            'data' => []
        ];
        $headers = [
            self::$headerName => [self::$mediaType]
        ];

        $response = Response::create(json_encode($content), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $response->assertJsonApiFetchedRelationships(new Collection([]));
    }

    /**
     * @test
     */
    public function responseFetchedToManyRelationships()
    {
        $collection = $this->createCollection();

        $resourceType = $collection->first()->getResourceType();
        $status = 200;
        $content = [
            'data' => $this->createResourceCollection($collection, true, false)
        ];
        $headers = [
            self::$headerName => [self::$mediaType]
        ];

        $response = Response::create(json_encode($content), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $response->assertJsonApiFetchedRelationships($collection, $resourceType);
    }

    /**
     * @test
     * @dataProvider responseFetchedToManyRelationshipsFailedProvider
     */
    public function responseFetchedToManyRelationshipsFailed(
        $status,
        $headers,
        $content,
        $model,
        $resourceType,
        $strict,
        $failureMsg
    ) {
        $response = Response::create(json_encode($content), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $this->setFailureException($failureMsg);

        $response->assertJsonApiFetchedRelationships($model, $resourceType, $strict);
    }

    public function responseFetchedToManyRelationshipsFailedProvider()
    {
        $status = 200;
        $headers = [
            self::$headerName => [self::$mediaType]
        ];
        $collection = $this->createCollection();
        $resourceType = $collection->first()->getResourceType();

        return [
            'wrong status' => [
                400,
                $headers,
                [
                    'data' => $this->createResourceCollection($collection, true, false)
                ],
                $collection,
                $resourceType,
                false,
                'Expected status code 200 but received 400.'
            ],
            'no headers' => [
                $status,
                [],
                [
                    'data' => $this->createResourceCollection($collection, true, false)
                ],
                $collection,
                $resourceType,
                false,
                'Header [Content-Type] not present on response.'
            ],
            'not valid structure' => [
                $status,
                $headers,
                [
                    'data' => $this->createResourceCollection($collection, true, false),
                    'anything' => 'not valid'
                ],
                $collection,
                $resourceType,
                false,
                Messages::ONLY_ALLOWED_MEMBERS
            ],
            'no data member' => [
                $status,
                $headers,
                [
                    'errors' => [
                        [
                            'status' => '400'
                        ]
                    ]
                ],
                $collection,
                $resourceType,
                false,
                sprintf(Messages::HAS_MEMBER, 'data')
            ],
            'not valid collection' => [
                $status,
                $headers,
                [
                    'data' => $this->createResourceCollection($collection, true, true)
                ],
                $collection,
                $resourceType,
                false,
                null
            ]
        ];
    }
}
