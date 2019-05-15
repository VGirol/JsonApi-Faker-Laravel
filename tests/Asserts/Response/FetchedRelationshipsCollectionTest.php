<?php
namespace VGirol\JsonApiAssert\Laravel\Tests\Asserts\Response;

use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Http\Response;
use VGirol\JsonApiAssert\Laravel\Factory\HelperFactory;
use VGirol\JsonApiAssert\Laravel\Tests\TestCase;
use VGirol\JsonApiAssert\Messages;

class FetchedRelationshipsCollectionTest extends TestCase
{
    /**
     * @test
     */
    public function responseFetchedEmptyToManyRelationships()
    {
        $strict = false;
        $status = 200;
        $content = [
            'data' => []
        ];
        $headers = [
            self::$headerName => [self::$mediaType]
        ];
        $expected = HelperFactory::create('collection', collect([]), $this->resourceType, $this->routeName, true)->toArray();

        $response = Response::create(json_encode($content), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $response->assertJsonApiFetchedRelationships($expected, $strict);
    }

    /**
     * @test
     */
    public function responseFetchedToManyRelationships()
    {
        $strict = false;
        $collection = $this->createCollection();
        $status = 200;
        $content = [
            'data' => $this->createResourceCollection($collection, true, null)
        ];
        $headers = [
            self::$headerName => [self::$mediaType]
        ];
        $expected = HelperFactory::create('collection', $collection, $this->resourceType, $this->routeName, true)->toArray();

        $response = Response::create(json_encode($content), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $response->assertJsonApiFetchedRelationships($expected, $strict);
    }

    /**
     * @test
     * @dataProvider responseFetchedToManyRelationshipsFailedProvider
     */
    public function responseFetchedToManyRelationshipsFailed(
        $status,
        $headers,
        $content,
        $expected,
        $strict,
        $failureMsg
    ) {
        $response = Response::create(json_encode($content), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $this->setFailureException($failureMsg);

        $response->assertJsonApiFetchedRelationships($expected, $strict);
    }

    public function responseFetchedToManyRelationshipsFailedProvider()
    {
        $status = 200;
        $headers = [
            self::$headerName => [self::$mediaType]
        ];
        $collection = $this->createCollection();
        $expected = HelperFactory::create('collection', $collection, $this->resourceType, $this->routeName, true)->toArray();

        return [
            'wrong status' => [
                400,
                $headers,
                [
                    'data' => $this->createResourceCollection($collection, true, null)
                ],
                $expected,
                false,
                'Expected status code 200 but received 400.'
            ],
            'no headers' => [
                $status,
                [],
                [
                    'data' => $this->createResourceCollection($collection, true, null)
                ],
                $expected,
                false,
                'Header [Content-Type] not present on response.'
            ],
            'not valid structure' => [
                $status,
                $headers,
                [
                    'data' => $this->createResourceCollection($collection, true, null),
                    'anything' => 'not valid'
                ],
                $expected,
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
                $expected,
                false,
                sprintf(Messages::HAS_MEMBER, 'data')
            ],
            'not valid collection' => [
                $status,
                $headers,
                [
                    'data' => $this->createResourceCollection($collection, true, 'value')
                ],
                $expected,
                false,
                null
            ]
        ];
    }
}
