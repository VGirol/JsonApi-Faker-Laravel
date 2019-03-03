<?php
namespace VGirol\JsonApiAssert\Laravel\Tests\Asserts;

use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\TestResponse;
use VGirol\JsonApiAssert\Laravel\Tests\TestCase;
use VGirol\JsonApiAssert\Assert as JsonApiAssert;
use VGirol\JsonApiAssert\Laravel\Tests\Tools\Models\ModelForTest;
use VGirol\JsonApiAssert\Messages;

class CreatedTest extends TestCase
{
    /**
     * @test
     */
    public function response_created()
    {
        $model = new ModelForTest();
        $model->setIdAttribute(1);
        $status = 201;
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
        $headers = [
            'Content-Type' => [$this->mediaType],
            'Location' => ['url']
        ];

        $response = Response::create(json_encode($content), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $response->assertJsonApiCreated($model, $model->getResourceType());
    }

    /**
     * @test
     * @dataProvider notValidResponseCreated
     */
    public function response_created_failed($model, $resourceType, $content, $failureMsg)
    {
        $fn = function ($model, $resourceType, $content) {
            $headers = [
                'Content-Type' => [$this->mediaType],
                'Location' => ['url']
            ];

            $response = Response::create(json_encode($content), 201, $headers);
            $response = TestResponse::fromBaseResponse($response);

            $response->assertJsonApiCreated($model, $resourceType);
        };

        JsonApiAssert::assertTestFail($fn, $failureMsg, $model, $resourceType, $content);
    }

    public function notValidResponseCreated()
    {
        $model = new ModelForTest([
            'TST_ID' => 10,
            'TST_NAME' => 'name',
            'TST_NUMBER' => 123,
            'TST_CREATION_DATE' => null
        ]);

        return [
            'no data' => [
                $model,
                $model->getResourceType(),
                [
                    'meta' => [
                        'bad' => 'response'
                    ]
                ],
                sprintf(Messages::HAS_MEMBER, 'data')
            ],
            'data not valid' => [
                $model,
                $model->getResourceType(),
                [
                    'data' => [
                        'type' => $model->getResourceType(),
                        'id' => '666',
                        'attributes' => [
                            'TST_ID' => 10,
                            'TST_NAME' => 'name',
                            'TST_NUMBER' => 123,
                            'TST_CREATION_DATE' => null
                        ]
                    ]
                ],
                null
            ],
            'data attributes member not valid' => [
                $model,
                $model->getResourceType(),
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
                null
            ],
            'location header not valid' => [
                $model,
                $model->getResourceType(),
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
                    ],
                    'links' => [
                        'self' => 'not-valid'
                    ]
                ],
                null
            ]
        ];
    }
}
