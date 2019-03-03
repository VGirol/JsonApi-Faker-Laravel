<?php
namespace VGirol\JsonApiAssert\Laravel\Tests\Asserts;

use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\TestResponse;
use VGirol\JsonApiAssert\Laravel\Tests\TestCase;
use VGirol\JsonApiAssert\Assert as JsonApiAssert;
use VGirol\JsonApiAssert\Laravel\Tests\Tools\Models\ModelForTest;

class UpdatedTest extends TestCase
{
    /**
     * @test
     * @dataProvider validResponseUpdated
     */
    public function response_updated($content, $model, $resourceType)
    {
        $status = 200;
        $headers = [
            'Content-Type' => [$this->mediaType]
        ];

        $response = Response::create(json_encode($content), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $response->assertJsonApiUpdated($model, $resourceType);
    }

    public function validResponseUpdated()
    {
        $model = new ModelForTest();
        $model->setIdAttribute(1);
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
                $model->getResourceType()
            ],
            'with meta' => [
                [
                    'meta' => [
                        'valid' => 'response'
                    ]
                    ],
                    null,
                    null
            ]
        ];
    }

    /**
     * @test
     * @dataProvider notValidResponseUpdated
     */
    public function response_updated_failed($status, $content, $model, $resourceType, $failureMsg)
    {
        $fn = function ($status, $content, $model, $resourceType) {
            $headers = [
                'Content-Type' => [$this->mediaType]
            ];

            $response = Response::create(json_encode($content), $status, $headers);
            $response = TestResponse::fromBaseResponse($response);

            $response->assertJsonApiUpdated($model, $resourceType);
        };

        JsonApiAssert::assertTestFail($fn, $failureMsg, $status, $content, $model, $resourceType);
    }

    public function notValidResponseUpdated()
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
                'Expected status code 200 but received 400.'
            ],
            'no meta nor data member' => [
                200,
                [
                    'errors' => [
                        [
                            'status' => '400'
                        ]
                    ]
                ],
                $model,
                $model->getResourceType(),
                null
            ],
            'data attributes member not valid' => [
                200,
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
                null
            ]
        ];
    }
}
