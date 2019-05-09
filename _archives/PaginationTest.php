<?php
namespace VGirol\JsonApiAssert\Laravel\Tests\Asserts\Response;

use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Http\Response;
use VGirol\JsonApiAssert\Laravel\Assert;
use VGirol\JsonApiAssert\Laravel\Tests\TestCase;
use VGirol\JsonApiAssert\Messages;

class PaginationTest extends TestCase
{
    /**
     * @test
     */
    public function response_pagination_links()
    {
        $status = 200;
        $headers = [
            'Content-Type' => [self::$mediaType]
        ];

        $content = [
            'links' => [
                'first' => 'url',
                'last' => 'url'
            ]
        ];
        $expected = [
            'first' => 'url',
            'last' => 'url'
        ];

        $response = Response::create(json_encode($content), $status, $headers);
        $response = TestResponse::fromBaseResponse($response);

        $response->assertJsonApiPaginationLinks($expected);
    }

    /**
     * @test
     * @dataProvider paginationLinksFailedProvider
     */
    public function response_pagination_links_failed($content, $expected, $failureMsg)
    {
        $fn = function ($content, $expected) {
            $status = 200;
            $headers = [
                'Content-Type' => [self::$mediaType]
            ];

            $response = Response::create(json_encode($content), $status, $headers);
            $response = TestResponse::fromBaseResponse($response);

            $response->assertJsonApiPaginationLinks($expected);
        };

        Assert::assertTestFail($fn, $failureMsg, $content, $expected);
    }

    public function paginationLinksFailedProvider()
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
                null
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
                null
            ]
        ];
    }
}
