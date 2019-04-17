<?php
namespace VGirol\JsonApiAssert\Laravel\Tests\Asserts\Response;

use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Http\Response;
use VGirol\JsonApiAssert\Laravel\Tests\TestCase;
use VGirol\JsonApiAssert\Messages;

class FetchedCollectionTest extends TestCase
{
    /**
     * @test
     */
    public function responseFetchedCollection()
    {
        $collection = $this->createCollection();
        $data = $this->createResourceCollection($collection, false, false);

        $resourceType = $collection->first()->getResourceType();
        $status = 200;
        $content = [
            'data' => $data
        ];
        $headers = [
            self::$headerName => [self::$mediaType]
        ];

        $response = Response::create(json_encode($content), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $response->assertJsonApiFetchedResourceCollection($collection, $resourceType);
    }

    /**
     * @test
     * @dataProvider responseFetchedCollectionFailedProvider
     */
    public function responseFetchedCollectionFailed(
        $status,
        $headers,
        $content,
        $collection,
        $resourceType,
        $failureMsg
    ) {
        $response = Response::create(json_encode($content), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $this->setFailureException($failureMsg);

        $response->assertJsonApiFetchedResourceCollection($collection, $resourceType);
    }

    public function responseFetchedCollectionFailedProvider()
    {
        $collection = $this->createCollection();
        $data = $this->createResourceCollection($collection, false, true);

        $resourceType = $collection->first()->getResourceType();
        $status = 200;
        $content = [
            'data' => $data
        ];
        $headers = [
            self::$headerName => [self::$mediaType]
        ];

        return [
            'bad status' => [
                400,
                $headers,
                $content,
                $collection,
                $resourceType,
                'Expected status code 200 but received 400.'
            ],
            'no headers' => [
                $status,
                [],
                $content,
                $collection,
                $resourceType,
                'Header [Content-Type] not present on response.'
            ],
            'no valid structure' => [
                $status,
                $headers,
                array_merge($content, ['anything' => 'not valid']),
                $collection,
                $resourceType,
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
                sprintf(Messages::HAS_MEMBER, 'data')
            ],
            'data not as expected' => [
                $status,
                $headers,
                $content,
                $collection,
                $resourceType,
                null
            ]
        ];
    }
}
