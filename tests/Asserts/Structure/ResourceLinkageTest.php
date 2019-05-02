<?php
namespace VGirol\JsonApiAssert\Laravel\Tests\Asserts\Structure;

use Illuminate\Support\Collection;
use VGirol\JsonApiAssert\Laravel\Assert;
use VGirol\JsonApiAssert\Laravel\Tests\TestCase;
use VGirol\JsonApiAssert\Laravel\Tests\Tools\Models\ModelForTest;
use VGirol\JsonApiAssert\Messages;

class ResourceLinkageTest extends TestCase
{
    /**
     * @test
     */
    public function assertResourceIdentifierEquals()
    {
        $expectedId = 10;
        $resourceType = 'test';
        $resource = [
            'type' => $resourceType,
            'id' => $expectedId
        ];

        Assert::assertResourceIdentifierEquals($expectedId, $resourceType, $resource);
    }

    /**
     * @test
     * @dataProvider assertResourceIdentifierEqualsFailedProvider
     */
    public function assertResourceIdentifierEqualsFailed($expectedId, $resourceType, $resource, $failureMsg)
    {
        $this->setFailureException($failureMsg);

        Assert::assertResourceIdentifierEquals($expectedId, $resourceType, $resource);
    }

    public function assertResourceIdentifierEqualsFailedProvider()
    {
        $resourceType = 'test';
        $expectedId = 10;

        return [
            'not same type' => [
                $expectedId,
                $resourceType,
                [
                    'type' => 'wrong',
                    'id' => $expectedId
                ],
                null
            ],
            'not same id' => [
                $expectedId,
                $resourceType,
                [
                    'type' => $resourceType,
                    'id' => $expectedId + 2
                ],
                null
            ]
        ];
    }

    /**
     * @test
     */
    public function assertResourceIdentifierCollectionEquals()
    {
        $refCollection = $this->createCollection();
        $collection = $this->createResourceCollection($refCollection, true, false);
        $resourceType = $refCollection[0]->getResourceType();

        Assert::assertResourceIdentifierCollectionEquals($refCollection, $resourceType, $collection);
    }

    /**
     * @test
     */
    public function assertResourceIdentifierCollectionEqualsInvalidArguments()
    {
        $ref = [];
        $this->setInvalidArgumentException(1, Collection::class, $ref);
        Assert::assertResourceIdentifierCollectionEquals($ref, 'test', []);
    }

    /**
     * @test
     * @dataProvider assertResourceIdentifierCollectionEqualsFailedProvider
     */
    public function assertResourceIdentifierCollectionEqualsFailed(
        $refCollection,
        $resourceType,
        $collection,
        $failureMsg
    ) {
        $this->setFailureException($failureMsg);

        Assert::assertResourceIdentifierCollectionEquals($refCollection, $resourceType, $collection);
    }

    public function assertResourceIdentifierCollectionEqualsFailedProvider()
    {
        $refCollection = $this->createCollection();
        $collection = $this->createResourceCollection($refCollection, true, true);
        $resourceType = $refCollection[0]->getResourceType();

        return [
            'resource identifier collection is not an array of objects' => [
                $refCollection,
                $resourceType,
                $collection[0],
                Messages::MUST_BE_ARRAY_OF_OBJECTS
            ],
            'resource linkage has not the expected count' => [
                $refCollection,
                $resourceType,
                [
                    $collection[0]
                ],
                null
            ],
            'resource linkage is not as expected' => [
                $refCollection,
                $resourceType,
                $collection,
                null
            ]
        ];
    }

    /**
     * @test
     * @dataProvider assertResourceLinkageEqualsProvider
     */
    public function assertResourceLinkageEquals($reference, $resourceType, $resLinkage, $strict)
    {
        Assert::assertResourceLinkageEquals($reference, $resourceType, $resLinkage, $strict);
    }

    public function assertResourceLinkageEqualsProvider()
    {
        $model = $this->createModel(0);
        $resourceType = $model->getResourceType();
        $collection = $this->createCollection();

        return [
            'resource linkage is null' => [
                null,
                $resourceType,
                null,
                false
            ],
            'resource linkage is a single resource identifier' => [
                $this->createModel(0),
                $resourceType,
                [
                    'type' => $resourceType,
                    'id' => strval($model->getKey())
                ],
                false
            ],
            'resource linkage is an empty array' => [
                new Collection,
                $resourceType,
                [],
                false
            ],
            'resource linkage is a collection of 1 item' => [
                new Collection([$model]),
                $resourceType,
                $this->createResourceCollection(new Collection([$model]), true, false),
                false
            ],
            'resource linkage is a collection' => [
                $collection,
                $resourceType,
                $this->createResourceCollection($collection, true, false),
                false
            ]
        ];
    }

    /**
     * @test
     * @dataProvider assertResourceLinkageEqualsFailedProvider
     */
    public function assertResourceLinkageEqualsFailed($reference, $resourceType, $resLinkage, $strict, $failureMsg)
    {
        $this->setFailureException($failureMsg);

        Assert::assertResourceLinkageEquals($reference, $resourceType, $resLinkage, $strict);
    }

    public function assertResourceLinkageEqualsFailedProvider()
    {
        $model = $this->createModel(0);
        $resourceType = $model->getResourceType();
        $collection = $this->createCollection();

        return [
            'resource linkage is not valid' => [
                $collection,
                $resourceType,
                [
                    'type' => $resourceType,
                    'id' => 10
                ],
                false,
                Messages::RESOURCE_ID_MEMBER_IS_NOT_STRING
            ],
            'resource linkage is not null' => [
                null,
                $resourceType,
                [
                    'type' => $resourceType,
                    'id' => '10'
                ],
                false,
                null
            ],
            'resource linkage is array of objects' => [
                $model,
                $resourceType,
                [
                    [
                        'type' => $resourceType,
                        'id' => '10'
                    ],
                    [
                        'type' => $resourceType,
                        'id' => '12'
                    ]
                ],
                false,
                Messages::MUST_NOT_BE_ARRAY_OF_OBJECTS
            ],
            'resource linkage is not as expected' => [
                $model,
                $resourceType,
                [
                    'type' => $resourceType,
                    'id' => strval($model->getKey() + 1)
                ],
                false,
                null
            ],
            'resource linkage is not an empty array' => [
                new Collection,
                $resourceType,
                [
                    [
                        'type' => $resourceType,
                        'id' => '10'
                    ]
                ],
                false,
                null
            ],
            'resource linkage is not the expected collection' => [
                $collection,
                $resourceType,
                $this->createResourceCollection($collection, true, true),
                false,
                null
            ]
        ];
    }
}
