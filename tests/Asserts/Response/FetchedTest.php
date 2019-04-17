<?php
namespace VGirol\JsonApiAssert\Laravel\Tests\Asserts;

use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Http\Response;
use VGirol\JsonApiAssert\Laravel\Tests\TestCase;
use VGirol\JsonApiAssert\Laravel\Tests\Tools\Models\ModelForTest;
use VGirol\JsonApiAssert\Messages;

class FetchedTest extends TestCase
{
    /**
     * @test
     */
    public function responseFetchedSingleResource()
    {
        $model = new ModelForTest([
            'TST_ID' => 3,
            'TST_NAME' => 'test',
            'TST_NUMBER' => 123,
            'TST_CREATION_DATE' => '01-01-1970'
        ]);

        $resourceType = $model->getResourceType();
        $status = 200;
        $content = [
            'data' => [
                'type' => $resourceType,
                'id' => strval($model->getKey()),
                'attributes' => $model->toArray()
            ]
        ];
        $headers = [
            self::$headerName => [self::$mediaType]
        ];

        $response = Response::create(json_encode($content), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $response->assertJsonApiFetchedSingleResource($model, $resourceType);
    }

    /**
     * @test
     * @dataProvider responseFetchedSingleResourceFailedProvider
     */
    public function responseFetchedSingleResourceFailed($status, $headers, $content, $model, $resourceType, $failureMsg)
    {
        $response = Response::create(json_encode($content), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $this->setFailureException($failureMsg);

        $response->assertJsonApiFetchedSingleResource($model, $resourceType);
    }

    public function responseFetchedSingleResourceFailedProvider()
    {
        $model = new ModelForTest([
            'TST_ID' => 10,
            'TST_NAME' => 'name',
            'TST_NUMBER' => 123,
            'TST_CREATION_DATE' => null
        ]);

        return [
            'bad status' => [
                400,
                [
                    self::$headerName => [self::$mediaType]
                ],
                [
                    'data' => [
                        'type' => $model->getResourceType(),
                        'id' => strval($model->getKey()),
                        'attributes' => $model->getAttributes()
                    ]
                ],
                $model,
                $model->getResourceType(),
                'Expected status code 200 but received 400.'
            ],
            'no headers' => [
                200,
                [],
                [
                    'data' => [
                        'type' => $model->getResourceType(),
                        'id' => strval($model->getKey()),
                        'attributes' => $model->getAttributes()
                    ]
                ],
                $model,
                $model->getResourceType(),
                'Header [Content-Type] not present on response.'
            ],
            'no valid structure' => [
                200,
                [
                    self::$headerName => [self::$mediaType]
                ],
                [
                    'data' => [
                        'type' => $model->getResourceType(),
                        'id' => strval($model->getKey()),
                        'attributes' => $model->getAttributes(),
                        'anything' => 'not valid'
                    ]
                ],
                $model,
                $model->getResourceType(),
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
                $model,
                $model->getResourceType(),
                sprintf(Messages::HAS_MEMBER, 'data')
            ],
            'data attributes member not valid' => [
                200,
                [
                    self::$headerName => [self::$mediaType]
                ],
                [
                    'data' => [
                        'type' => $model->getResourceType(),
                        'id' => strval($model->getKey()),
                        'attributes' => [
                            'TST_ID' => $model->getKey(),
                            'TST_NAME' => 'name',
                            'TST_NUMBER' => 666,
                            'TST_CREATION_DATE' => null
                        ]
                    ]
                ],
                $model,
                $model->getResourceType(),
                null
            ]
        ];
    }
}
