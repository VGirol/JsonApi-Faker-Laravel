<?php
namespace VGirol\JsonApiAssert\Tests\Asserts;

use VGirol\JsonApiAssert\Assert as JsonApiAssert;
use VGirol\JsonApiAssert\Tests\TestCase;
use VGirol\JsonApiAssert\Messages;

class BaseTest extends TestCase
{
    /**
     * @test
     */
    public function assert_has_member()
    {
        $data = [
            'data' => 'jsonapi',
            'meta' => 'valid'
        ];

        JsonApiAssert::assertHasMember($data, 'data');
    }

    /**
     * @test
     */
    public function assert_has_member_failed()
    {
        $data = [
            'anything' => 'else'
        ];
        $key = 'nothing';

        $fn = function ($data, $key) {
            JsonApiAssert::assertHasMember($data, $key);
        };

        JsonApiAssert::assertTestFail(
            $fn,
            sprintf(Messages::HAS_MEMBER, $key),
            $data,
            $key
        );
    }

    /**
     * @test
     */
    public function assert_not_has_member()
    {
        $data = [
            'data' => 'jsonapi',
            'meta' => 'valid'
        ];

        JsonApiAssert::assertNotHasMember($data, 'test');
    }

    /**
     * @test
     */
    public function assert_not_has_member_failed()
    {
        $data = [
            'anything' => 'else'
        ];
        $key = 'anything';

        $fn = function ($data, $key) {
            JsonApiAssert::assertNotHasMember($data, $key);
        };

        JsonApiAssert::assertTestFail(
            $fn,
            sprintf(Messages::NOT_HAS_MEMBER, $key),
            $data,
            $key
        );
    }

    /**
     * @test
     */
    public function assert_has_data()
    {
        $data = [
            'meta' => 'valid',
            'data' => 'jsonapi'
        ];

        JsonApiAssert::assertHasData($data);
    }

    /**
     * @test
     */
    public function assert_has_attributes()
    {
        $data = [
            'meta' => 'valid',
            'attributes' => 'jsonapi'
        ];

        JsonApiAssert::assertHasAttributes($data);
    }

    /**
     * @test
     */
    public function assert_has_links()
    {
        $data = [
            'meta' => 'valid',
            'links' => 'jsonapi'
        ];

        JsonApiAssert::assertHasLinks($data);
    }

    /**
     * @test
     */
    public function assert_has_meta()
    {
        $data = [
            'meta' => 'valid',
            'links' => 'jsonapi'
        ];

        JsonApiAssert::assertHasMeta($data);
    }

    /**
     * @test
     */
    public function assert_has_included()
    {
        $data = [
            'meta' => 'valid',
            'included' => 'jsonapi'
        ];

        JsonApiAssert::assertHasIncluded($data);
    }

    /**
     * @test
     */
    public function assert_has_relationships()
    {
        $data = [
            'meta' => 'valid',
            'relationships' => 'jsonapi'
        ];

        JsonApiAssert::assertHasRelationships($data);
    }

    /**
     * @test
     */
    public function assert_has_errors()
    {
        $data = [
            'meta' => 'valid',
            'errors' => 'jsonapi'
        ];

        JsonApiAssert::assertHasErrors($data);
    }

    /**
     * @test
     */
    public function assert_contains_at_least_one_member()
    {
        $expected = ['first', 'second', 'meta'];
        $data = [
            'meta' => 'valid',
            'errors' => 'jsonapi'
        ];

        JsonApiAssert::assertContainsAtLeastOneMember($expected, $data);
    }

    /**
     * @test
     */
    public function assert_contains_at_least_one_member_failed()
    {
        $expected = ['first', 'second'];
        $data = [
            'meta' => 'valid',
            'errors' => 'jsonapi'
        ];

        $fn = function ($expected, $data) {
            JsonApiAssert::assertContainsAtLeastOneMember($expected, $data);
        };

        JsonApiAssert::assertTestFail(
            $fn,
            sprintf(Messages::CONTAINS_AT_LEAST_ONE, implode(', ', $expected)),
            $expected,
            $data
        );
    }

    /**
     * @test
     */
    public function assert_contains_only_allowed_members()
    {
        $expected = ['first', 'second', 'meta'];
        $data = [
            'meta' => 'valid',
            'first' => 'jsonapi'
        ];

        JsonApiAssert::assertContainsOnlyAllowedMembers($expected, $data);
    }

    /**
     * @test
     */
    public function assert_contains_only_allowed_members_failed()
    {
        $expected = ['first', 'second', 'meta'];
        $data = [
            'meta' => 'valid',
            'errors' => 'jsonapi'
        ];

        $fn = function ($expected, $data) {
            JsonApiAssert::assertContainsOnlyAllowedMembers($expected, $data);
        };

        JsonApiAssert::assertTestFail(
            $fn,
            Messages::ONLY_ALLOWED_MEMBERS,
            $expected,
            $data
        );
    }

    /**
     * @test
     */
    public function assert_is_array_of_objects()
    {
        $data = [
            [
                'meta' => 'valid'
            ],
            [
                'first' => 'jsonapi'
            ]
        ];

        JsonApiAssert::assertIsArrayOfObjects($data);
    }

    /**
     * @test
     * @dataProvider notArrayOfObjectsProvider
     */
    public function assert_is_array_of_objects_failed($data, $failureMessage)
    {
        $fn = function ($data) {
            JsonApiAssert::assertIsArrayOfObjects($data);
        };

        JsonApiAssert::assertTestFail(
            $fn,
            $failureMessage,
            $data
        );
    }

    public function notArrayOfObjectsProvider()
    {
        return [
            'not an array' => [
                'error',
                null
            ],
            'associative array' => [
                [
                    'meta' => 'valid',
                    'errors' => 'jsonapi'
                ],
                null
            ]
        ];
    }

    /**
     * @test
     */
    public function assert_is_not_array_of_objects()
    {
        $data = [
            'meta' => 'valid',
            'first' => 'jsonapi'
        ];

        JsonApiAssert::assertIsNotArrayOfObjects($data);
    }

    /**
     * @test
     */
    public function assert_is_not_array_of_objects_failed()
    {
        $data = [
            [
                'meta' => 'valid'
            ],
            [
                'first' => 'jsonapi'
            ]
        ];

        $fn = function ($data) {
            JsonApiAssert::assertIsNotArrayOfObjects($data);
        };

        JsonApiAssert::assertTestFail(
            $fn,
            null,
            $data
        );
    }
}
