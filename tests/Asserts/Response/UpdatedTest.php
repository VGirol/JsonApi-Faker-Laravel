<?php
namespace VGirol\JsonApiAssert\Laravel\Tests\Asserts\Response;

use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Http\Response;
use VGirol\JsonApiAssert\Laravel\Tests\TestCase;
use VGirol\JsonApiAssert\Laravel\Tests\Tools\Models\ModelForTest;
use VGirol\JsonApiAssert\Messages;

class UpdatedTest extends TestCase
{
    /**
     * @test
     * @dataProvider responseUpdatedProvider
     */
    public function responseUpdated($content, $model, $resourceType, $strict)
    {
        $status = 200;
        $headers = [
            static::$headerName => [static::$mediaType]
        ];

        $response = Response::create(json_encode($content), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $response->assertJsonApiUpdated($model, $resourceType, $strict);
    }

    public function responseUpdatedProvider()
    {
        $model = new ModelForTest();
        $model->setAttribute('TST_ID', 1);
        $content = [
            'data' => [
                'type' => $model->getResourceType(),
                'id' => strval($model->getKey()),
                'attributes' => $model->toArray(),
                'links' => [
                    'self' => 'url'
                ]
            ]
        ];

        return [
            'with data' => [
                $content,
                $model,
                $model->getResourceType(),
                false
            ],
            'with meta' => [
                [
                    'meta' => [
                        'valid' => 'response'
                    ]
                ],
                null,
                null,
                false
            ]
        ];
    }

    /**
     * @test
     * @dataProvider responseUpdatedFailedProvider
     */
    public function responseUpdatedFailed($status, $headers, $content, $model, $resourceType, $strict, $failureMsg)
    {
        $response = Response::create(json_encode($content), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $this->setFailureException($failureMsg);

        $response->assertJsonApiUpdated($model, $resourceType, $strict);
    }

    public function responseUpdatedFailedProvider()
    {
        $model = new ModelForTest([
            'TST_ID' => 10,
            'TST_NAME' => 'name',
            'TST_NUMBER' => 123,
            'TST_CREATION_DATE' => null
        ]);

        return [
            'wrong status code' => [
                202,
                [
                    static::$headerName => [static::$mediaType]
                ],
                [
                    'data' => [
                        'type' => $model->getResourceType(),
                        'id' => '10',
                        'attributes' => [
                            'TST_ID' => 10,
                            'TST_NAME' => 'name',
                            'TST_NUMBER' => 123,
                            'TST_CREATION_DATE' => null
                        ]
                    ]
                ],
                $model,
                $model->getResourceType(),
                false,
                'Expected status code 200 but received 202.'
            ],
            'no content-type header' => [
                200,
                [],
                [
                    'data' => [
                        'type' => $model->getResourceType(),
                        'id' => '10',
                        'attributes' => [
                            'TST_ID' => 10,
                            'TST_NAME' => 'name',
                            'TST_NUMBER' => 123,
                            'TST_CREATION_DATE' => null
                        ]
                    ]
                ],
                $model,
                $model->getResourceType(),
                false,
                'Header [Content-Type] not present on response.'
            ],
            'no valid structure' => [
                200,
                [
                    static::$headerName => [static::$mediaType]
                ],
                [
                    'data' => [
                        'type' => $model->getResourceType(),
                        'id' => 10,
                        'attributes' => [
                            'TST_ID' => 10,
                            'TST_NAME' => 'name',
                            'TST_NUMBER' => 123,
                            'TST_CREATION_DATE' => null
                        ]
                    ]
                ],
                $model,
                $model->getResourceType(),
                false,
                Messages::RESOURCE_ID_MEMBER_IS_NOT_STRING
            ],
            'no meta nor data member' => [
                200,
                [
                    static::$headerName => [static::$mediaType]
                ],
                [
                    'errors' => [
                        [
                            'status' => '406',
                            'title' => 'Not Acceptable',
                            'details' => 'description'
                        ]
                    ]
                ],
                $model,
                $model->getResourceType(),
                false,
                null
            ],
            'data attributes member not valid' => [
                200,
                [
                    static::$headerName => [static::$mediaType]
                ],
                [
                    'data' => [
                        'type' => $model->getResourceType(),
                        'id' => '10',
                        'attributes' => [
                            'TST_ID' => 10,
                            'TST_NAME' => 'name',
                            'TST_NUMBER' => 666,
                            'TST_CREATION_DATE' => null
                        ]
                    ]
                ],
                $model,
                $model->getResourceType(),
                false,
                null
            ]
        ];
    }
}
