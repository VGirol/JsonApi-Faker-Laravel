<?php
namespace VGirol\JsonApiAssert\Laravel\Tests\Asserts\Response;

use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Http\Response;
use VGirol\JsonApiAssert\Laravel\Tests\TestCase;
use VGirol\JsonApiAssert\Messages;

class CreatedTest extends TestCase
{
    /**
     * @test
     * @dataProvider responseCreatedCreated
     */
    public function responseCreated($withLocationHeader)
    {
        $model = $this->createModel();
        $status = 201;
        $content = [
            'data' => $this->createResource($model, false, false, [
                'links' => [
                    'self' => 'url'
                ]
            ])
        ];
        $headers = [
            static::$headerName => [static::$mediaType]
        ];
        if ($withLocationHeader) {
            $headers['Location'] = ['url'];
        }

        $response = Response::create(json_encode($content), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $response->assertJsonApiCreated($model, $model->getResourceType());
    }

    public function responseCreatedCreated()
    {
        return [
            'with Location header' => [
                true
            ],
            'without Location header' => [
                false
            ]
        ];
    }

    /**
     * @test
     * @dataProvider notValidResponseCreated
     */
    public function responseCreatedFailed($model, $resourceType, $code, $headers, $content, $failureMsg)
    {
        $response = Response::create(json_encode($content), $code, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $this->setFailureException($failureMsg);

        $response->assertJsonApiCreated($model, $resourceType);
    }

    public function notValidResponseCreated()
    {
        $headers = [
            static::$headerName => [static::$mediaType]
        ];
        $model = $this->createModel();

        return [
            'wrong status code' => [
                $model,
                $model->getResourceType(),
                200,
                $headers,
                [
                    'data' => $this->createResource($model, false, false, [
                        'links' => [
                            'self' => 'url'
                        ]
                    ])
                ],
                'Expected status code 201 but received 200.'
            ],
            'no content-type header' => [
                $model,
                $model->getResourceType(),
                201,
                [],
                [
                    'data' => $this->createResource($model, false, false, [
                        'links' => [
                            'self' => 'url'
                        ]
                    ])
                ],
                'Header [Content-Type] not present on response.'
            ],
            'no valid structure' => [
                $model,
                $model->getResourceType(),
                201,
                $headers,
                [
                    'data' => [
                        'type' => $model->getResourceType(),
                        'id' => $model->getKey(),
                        'attributes' => $model->toArray(),
                        'links' => [
                            'self' => 'url'
                        ]
                    ]
                ],
                Messages::RESOURCE_ID_MEMBER_IS_NOT_STRING
            ],
            'no data' => [
                $model,
                $model->getResourceType(),
                201,
                $headers,
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
                201,
                $headers,
                [
                    'data' => $this->createResource($model, true, false)
                ],
                sprintf(Messages::HAS_MEMBER, 'attributes')
            ],
            'location header not valid' => [
                $model,
                $model->getResourceType(),
                201,
                [
                    static::$headerName => [static::$mediaType],
                    'Location' => 'bad'
                ],
                [
                    'data' => $this->createResource($model, false, false, [
                        'links' => [
                            'self' => 'url'
                        ]
                    ])
                ],
                null
            ]
        ];
    }
}
