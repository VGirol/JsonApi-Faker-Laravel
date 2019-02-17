<?php
namespace VGirol\JsonApiAssert\Tests;

use VGirol\JsonApiAssert\JsonApiAssert;
use PHPUnit\Framework\Assert as PHPUnit;
use VGirol\JsonApiAssert\Tests\TestCase;
use VGirol\JsonApiAssert\JsonApiAssertMessages;

class JsonApiBaseTest extends TestCase
{
    use JsonApiAssert;

    /**
     * @test
     */
    public function assert_has_member()
    {
        $data = [
            'data' => 'jsonapi',
            'meta' => 'valid'
        ];

        $this->assertHasMember($data, 'data');
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
            $this->assertHasMember($data, $key);
        };

        $this->assertTestFail(
            $fn,
            sprintf(JsonApiAssertMessages::JSONAPI_ERROR_HAS_MEMBER, $key),
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

        $this->assertNotHasMember($data, 'test');
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
            $this->assertNotHasMember($data, $key);
        };

        $this->assertTestFail(
            $fn,
            sprintf(JsonApiAssertMessages::JSONAPI_ERROR_NOT_HAS_MEMBER, $key),
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

        $this->assertHasData($data);
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

        $this->assertHasAttributes($data);
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

        $this->assertHasLinks($data);
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

        $this->assertHasMeta($data);
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

        $this->assertHasIncluded($data);
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

        $this->assertHasRelationships($data);
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

        $this->assertHasErrors($data);
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

        $this->assertContainsAtLeastOneMember($expected, $data);
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
            $this->assertContainsAtLeastOneMember($expected, $data);
        };

        $this->assertTestFail(
            $fn,
            sprintf(JsonApiAssertMessages::JSONAPI_ERROR_CONTAINS_AT_LEAST_ONE, implode(', ', $expected)),
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

        $this->assertContainsOnlyAllowedMembers($expected, $data);
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
            $this->assertContainsOnlyAllowedMembers($expected, $data);
        };

        $this->assertTestFail(
            $fn,
            JsonApiAssertMessages::JSONAPI_ERROR_ONLY_ALLOWED_MEMBERS,
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

        $this->assertIsArrayOfObjects($data);
    }

    /**
     * @test
     * @dataProvider notArrayOfObjectsProvider
     */
    public function assert_is_array_of_objects_failed($data, $failureMessage)
    {
        $fn = function ($data) {
            $this->assertIsArrayOfObjects($data);
        };

        $this->assertTestFail(
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

        $this->assertIsNotArrayOfObjects($data);
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
            $this->assertIsNotArrayOfObjects($data);
        };

        $this->assertTestFail(
            $fn,
            null,
            $data
        );
    }
}
