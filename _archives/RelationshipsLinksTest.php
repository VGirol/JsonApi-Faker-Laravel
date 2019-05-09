<?php
namespace VGirol\JsonApiAssert\Laravel\Tests\Asserts\Response;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Http\Response;
use VGirol\JsonApiAssert\Laravel\Assert;
use VGirol\JsonApiAssert\Laravel\Tests\TestCase;
use VGirol\JsonApiAssert\Laravel\Tests\Tools\Models\ModelForTest;
use VGirol\JsonApiAssert\Messages;

class FetchedRelationshipsCollectionTest extends TestCase
{
    /**
     * @test
     */
    public function response_relationships_links()
    {
        $status = 200;
        $headers = [
            'Content-Type' => [self::$mediaType]
        ];

        $content = [
            'data' => [
                'relationships' => [
                    'test' => [
                        'links' => [
                            'self' => 'url',
                            'related' => 'url'
                        ]
                    ]
                ]
            ]
        ];
        $expected = [
            'self' => 'url',
            'related' => 'url'
        ];
        $path = 'data.relationships.test';

        $response = Response::create(json_encode($content), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $response->assertJsonApiRelationshipsLinks($expected, $path);
    }

    /**
     * @test
     * @dataProvider relationshipsLinksFailedProvider
     */
    public function response_relationships_links_failed($content, $expected, $path, $failureMsg)
    {
        $fn = function ($content, $expected, $path) {
            $status = 200;
            $headers = [
                'Content-Type' => [self::$mediaType]
            ];

            $response = Response::create(json_encode($content), $status, $headers);
            $response = TestResponse::fromBaseResponse($response);

            $response->assertJsonApiRelationshipsLinks($expected, $path);
        };

        Assert::assertTestFail($fn, $failureMsg, $content, $expected, $path);
    }

    public function relationshipsLinksFailedProvider()
    {
        return [
            'no links member' => [
                [
                    'meta' => [
                        'bad' => 'content'
                    ]
                ],
                [
                    'first' => 'url',
                    'last' => 'url'
                ],
                'meta',
                sprintf(Messages::HAS_MEMBER, 'links')
            ],
            'bad links member' => [
                [
                    'links' => [
                        'first' => 'url',
                        'bad' => 'url'
                    ]
                ],
                [
                    'first' => 'url',
                    'last' => 'url'
                ],
                null,
                Messages::ONLY_ALLOWED_MEMBERS
            ],
            'no match' => [
                [
                    'links' => [
                        'first' => 'url',
                        'last' => 'url2'
                    ]
                ],
                [
                    'first' => 'url',
                    'last' => 'url'
                ],
                null,
                null
            ]
        ];
    }
}
