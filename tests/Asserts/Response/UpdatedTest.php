<?php
namespace VGirol\JsonApiAssert\Laravel\Tests\Asserts\Response;

use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Http\Response;
use VGirol\JsonApiAssert\Laravel\Factory\HelperFactory;
use VGirol\JsonApiAssert\Laravel\Tests\TestCase;
use VGirol\JsonApiAssert\Messages;

class UpdatedTest extends TestCase
{
    /**
     * @test
     * @dataProvider responseUpdatedProvider
     */
    public function responseUpdated($content, $expected, $strict)
    {
        $status = 200;
        $headers = [
            static::$headerName => [static::$mediaType]
        ];

        $response = Response::create(json_encode($content), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $response->assertJsonApiUpdated($expected, $strict);
    }

    public function responseUpdatedProvider()
    {
        $selfUrl = 'url';
        $additional = [
            'links' => [
                'self' => $selfUrl
            ]
        ];

        $model = $this->createModel();
        $content = [
            'data' => $this->createResource($model, false, null, $additional)
        ];

        $expected = HelperFactory::create('resource-object', $model, $this->resourceType, $this->routeName)
            ->addLink('self', $selfUrl)
            ->toArray();

        return [
            'with data' => [
                $content,
                $expected,
                false
            ],
            'with meta' => [
                [
                    'meta' => [
                        'valid' => 'response'
                    ]
                ],
                null,
                false
            ]
        ];
    }

    /**
     * @test
     * @dataProvider responseUpdatedFailedProvider
     */
    public function responseUpdatedFailed($status, $headers, $content, $expected, $strict, $failureMsg)
    {
        $response = Response::create(json_encode($content), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $this->setFailureException($failureMsg);

        $response->assertJsonApiUpdated($expected, $strict);
    }

    public function responseUpdatedFailedProvider()
    {
        $model = $this->createModel();

        $expected = HelperFactory::create('resource-object', $model, $this->resourceType, $this->routeName)->toArray();

        return [
            'wrong status code' => [
                202,
                [
                    static::$headerName => [static::$mediaType]
                ],
                [
                    'data' => $this->createResource($model, false)
                ],
                $expected,
                false,
                'Expected status code 200 but received 202.'
            ],
            'no content-type header' => [
                200,
                [],
                [
                    'data' => $this->createResource($model, false)
                ],
                $expected,
                false,
                'Header [Content-Type] not present on response.'
            ],
            'no valid structure' => [
                200,
                [
                    static::$headerName => [static::$mediaType]
                ],
                [
                    'data' => $this->createResource($model, false, 'structure')
                ],
                $expected,
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
                $expected,
                false,
                null
            ],
            'data attributes member not valid' => [
                200,
                [
                    static::$headerName => [static::$mediaType]
                ],
                [
                    'data' => $this->createResource($model, false, 'value')
                ],
                $expected,
                false,
                null
            ]
        ];
    }
}
