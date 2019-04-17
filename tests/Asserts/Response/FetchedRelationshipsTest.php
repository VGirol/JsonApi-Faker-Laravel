<?php
namespace VGirol\JsonApiAssert\Laravel\Tests\Asserts\Response;

use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Http\Response;
use VGirol\JsonApiAssert\Laravel\Tests\TestCase;
use VGirol\JsonApiAssert\Messages;

class FetchedRelationshipsTest extends TestCase
{
    /**
     * @test
     */
    public function responseFetchedEmptyToOneRelationships()
    {
        $status = 200;
        $content = [
            'data' => null
        ];
        $headers = [
            self::$headerName => [self::$mediaType]
        ];

        $response = Response::create(json_encode($content), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $response->assertJsonApiFetchedRelationships(null);
    }

    /**
     * @test
     */
    public function responseFetchedToOneRelationships()
    {
        $model = $this->createModel();
        $resourceType = $model->getResourceType();
        $status = 200;
        $content = [
            'data' => $this->createResource($model, true, false)
        ];
        $headers = [
            self::$headerName => [self::$mediaType]
        ];

        $response = Response::create(json_encode($content), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $response->assertJsonApiFetchedRelationships($model, $resourceType);
    }

    /**
     * @test
     * @dataProvider responseFetchedToOneRelationshipsFailedProvider
     */
    public function responseFetchedToOneRelationshipsFailed(
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

    public function responseFetchedToOneRelationshipsFailedProvider()
    {
        $status = 200;
        $headers = [
            self::$headerName => [self::$mediaType]
        ];
        $model = $this->createModel();

        return [
            'wrong status' => [
                400,
                $headers,
                [
                    'data' => $this->createResource($model, true, false)
                ],
                $model,
                $model->getResourceType(),
                false,
                'Expected status code 200 but received 400.'
            ],
            'no headers' => [
                $status,
                [],
                [
                    'data' => $this->createResource($model, true, false)
                ],
                $model,
                $model->getResourceType(),
                false,
                'Header [Content-Type] not present on response.'
            ],
            'structure not valid' => [
                $status,
                $headers,
                [
                    'data' => $this->createResource($model, true, false),
                    'anything' => 'not valid'
                ],
                $model,
                $model->getResourceType(),
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
                $model,
                $model->getResourceType(),
                false,
                sprintf(Messages::HAS_MEMBER, 'data')
            ],
            'resource linkage not valid' => [
                $status,
                $headers,
                [
                    'data' => $this->createResource($model, true, true)
                ],
                $model,
                $model->getResourceType(),
                false,
                null
            ]
        ];
    }
}
