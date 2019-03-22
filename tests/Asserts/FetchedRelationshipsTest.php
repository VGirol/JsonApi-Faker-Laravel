<?php
namespace VGirol\JsonApiAssert\Laravel\Tests\Asserts;

use Illuminate\Http\Response;
use VGirol\JsonApiAssert\Messages;
use Illuminate\Foundation\Testing\TestResponse;
use VGirol\JsonApiAssert\Laravel\Tests\TestCase;
use VGirol\JsonApiAssert\Assert as JsonApiAssert;
use VGirol\JsonApiAssert\Laravel\Tests\Tools\Models\ModelForTest;

class FetchedRelationshipsTest extends TestCase
{
    /**
     * @test
     */
    public function response_fetched_empty_to_one_relationships()
    {
        $status = 200;
        $content = [
            'data' => null
        ];
        $headers = [
            'Content-Type' => [$this->mediaType]
        ];

        $response = Response::create(json_encode($content), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $response->assertJsonApiFetchedToOneRelationships();
    }

    /**
     * @test
     */
    public function response_fetched_to_one_relationships()
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
                'id' => strval($model->getKey())
            ]
        ];
        $headers = [
            'Content-Type' => [$this->mediaType]
        ];

        $response = Response::create(json_encode($content), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $response->assertJsonApiFetchedToOneRelationships($model, $resourceType);
    }

    /**
     * @test
     * @dataProvider notValidResponseToOneRelationships
     */
    public function response_fetched_to_one_relationships_failed($status, $content, $model, $resourceType, $failureMsg)
    {
        $fn = function ($status, $content, $model, $resourceType) {
            $headers = [
                'Content-Type' => [$this->mediaType]
            ];

            $response = Response::create(json_encode($content), $status, $headers);
            $response = TestResponse::fromBaseResponse($response);

            $response->assertJsonApiFetchedToOneRelationships($model, $resourceType);
        };

        JsonApiAssert::assertTestFail($fn, $failureMsg, $status, $content, $model, $resourceType);
    }

    public function notValidResponseToOneRelationships()
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
            'no data member' => [
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
                sprintf(Messages::HAS_MEMBER, 'data')
            ],
            'resource linkage not valid' => [
                200,
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
                null
            ]
        ];
    }
}
