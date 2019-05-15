<?php
namespace VGirol\JsonApiAssert\Laravel\Tests\Asserts;

use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Http\Response;
use VGirol\JsonApiAssert\Laravel\Factory\HelperFactory;
use VGirol\JsonApiAssert\Laravel\Tests\TestCase;
use VGirol\JsonApiAssert\Laravel\Tests\Tools\Models\DummyModel;
use VGirol\JsonApiAssert\Messages;

class FetchedTest extends TestCase
{
    /**
     * @test
     */
    public function responseFetchedSingleResource()
    {
        $model = new DummyModel([
            'TST_ID' => 3,
            'TST_NAME' => 'test',
            'TST_NUMBER' => 123,
            'TST_CREATION_DATE' => '01-01-1970'
        ]);

        $status = 200;
        $content = [
            'data' => [
                'type' => $this->resourceType,
                'id' => strval($model->getKey()),
                'attributes' => $model->toArray()
            ]
        ];
        $headers = [
            self::$headerName => [self::$mediaType]
        ];

        $response = Response::create(json_encode($content), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $expected = HelperFactory::create('resource-object', $model, $this->resourceType, $this->routeName)->toArray();

        $response->assertJsonApiFetchedSingleResource($expected);
    }

    /**
     * @test
     * @dataProvider responseFetchedSingleResourceFailedProvider
     */
    public function responseFetchedSingleResourceFailed($status, $headers, $content, $expected, $failureMsg)
    {
        $response = Response::create(json_encode($content), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $this->setFailureException($failureMsg);

        $response->assertJsonApiFetchedSingleResource($expected);
    }

    public function responseFetchedSingleResourceFailedProvider()
    {
        $model = new DummyModel([
            'TST_ID' => 10,
            'TST_NAME' => 'name',
            'TST_NUMBER' => 123,
            'TST_CREATION_DATE' => null
        ]);

        $expected = HelperFactory::create('resource-object', $model, $this->resourceType, $this->routeName)->toArray();

        return [
            'bad status' => [
                400,
                [
                    self::$headerName => [self::$mediaType]
                ],
                [
                    'data' => [
                        'type' => $this->resourceType,
                        'id' => strval($model->getKey()),
                        'attributes' => $model->getAttributes()
                    ]
                ],
                $expected,
                'Expected status code 200 but received 400.'
            ],
            'no headers' => [
                200,
                [],
                [
                    'data' => [
                        'type' => $this->resourceType,
                        'id' => strval($model->getKey()),
                        'attributes' => $model->getAttributes()
                    ]
                ],
                $expected,
                'Header [Content-Type] not present on response.'
            ],
            'no valid structure' => [
                200,
                [
                    self::$headerName => [self::$mediaType]
                ],
                [
                    'data' => [
                        'type' => $this->resourceType,
                        'id' => strval($model->getKey()),
                        'attributes' => $model->getAttributes(),
                        'anything' => 'not valid'
                    ]
                ],
                $expected,
                Messages::ONLY_ALLOWED_MEMBERS
            ],
            'no data member' => [
                200,
                [
                    self::$headerName => [self::$mediaType]
                ],
                [
                    'errors' => [
                        [
                            'status' => '400'
                        ]
                    ]
                ],
                $expected,
                sprintf(Messages::HAS_MEMBER, 'data')
            ],
            'data attributes member not valid' => [
                200,
                [
                    self::$headerName => [self::$mediaType]
                ],
                [
                    'data' => [
                        'type' => $this->resourceType,
                        'id' => strval($model->getKey()),
                        'attributes' => [
                            'TST_ID' => $model->getKey(),
                            'TST_NAME' => 'name',
                            'TST_NUMBER' => 666,
                            'TST_CREATION_DATE' => null
                        ]
                    ]
                ],
                $expected,
                null
            ]
        ];
    }
}
