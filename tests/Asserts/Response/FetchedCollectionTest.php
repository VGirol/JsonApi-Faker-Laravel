<?php
namespace VGirol\JsonApiAssert\Laravel\Tests\Asserts\Response;

use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Http\Response;
use VGirol\JsonApiAssert\Laravel\Factory\HelperFactory;
use VGirol\JsonApiAssert\Laravel\Tests\TestCase;
use VGirol\JsonApiAssert\Messages;

class FetchedCollectionTest extends TestCase
{
    /**
     * @test
     */
    public function responseFetchedCollection()
    {
        $strict = false;
        $collection = $this->createCollection();
        $status = 200;
        $content = [
            'data' => $this->createResourceCollection($collection, false, null)
        ];
        $headers = [
            self::$headerName => [self::$mediaType]
        ];
        $expected = HelperFactory::create('collection', $collection, $this->resourceType, $this->routeName, false)->toArray();

        $response = Response::create(json_encode($content), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $response->assertJsonApiFetchedResourceCollection($expected, $strict);
    }

    /**
     * @test
     * @dataProvider responseFetchedCollectionFailedProvider
     */
    public function responseFetchedCollectionFailed(
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

        $response->assertJsonApiFetchedResourceCollection($expected, $strict);
    }

    public function responseFetchedCollectionFailedProvider()
    {
        $collection = $this->createCollection();
        $status = 200;
        $headers = [
            self::$headerName => [self::$mediaType]
        ];
        $expected = HelperFactory::create('collection', $collection, $this->resourceType, $this->routeName, false)->toArray();

        return [
            'bad status' => [
                400,
                $headers,
                [
                    'data' => $this->createResourceCollection($collection, false, null)
                ],
                $expected,
                false,
                'Expected status code 200 but received 400.'
            ],
            'no headers' => [
                $status,
                [],
                [
                    'data' => $this->createResourceCollection($collection, false, null)
                ],
                $expected,
                false,
                'Header [Content-Type] not present on response.'
            ],
            'no valid structure' => [
                $status,
                $headers,
                [
                    'data' => $this->createResourceCollection($collection, false, null),
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
            'data not as expected' => [
                $status,
                $headers,
                [
                    'data' => $this->createResourceCollection($collection, false, 'value'),
                    'anything' => 'not valid'
                ],
                $expected,
                false,
                null
            ]
        ];
    }
}
