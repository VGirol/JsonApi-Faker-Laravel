<?php
namespace VGirol\JsonApiAssert\Laravel\Tests\Asserts\Structure;

use Illuminate\Support\Collection;
use VGirol\JsonApiAssert\Laravel\Assert;
use VGirol\JsonApiAssert\Laravel\Tests\TestCase;
use VGirol\JsonApiAssert\Laravel\Tests\Tools\Models\ModelForTest;
use VGirol\JsonApiAssert\Messages;

class ResourceCollectionTest extends TestCase
{
    /**
     * @test
     */
    public function assertResourceCollectionEqualsCollection()
    {
        $expected = new Collection();
        $collection = [];
        for ($i = 1; $i < 5; $i++) {
            $attributes = [
                'TST_ID' => 10 + $i,
                'TST_NAME' => 'test' . $i,
                'TST_NUMBER' => 1000 * $i + 123,
                'TST_CREATION_DATE' => null
            ];

            $model = new ModelForTest();
            $model->setRawAttributes($attributes);
            $expected->push($model);

            $resource = [
                'type' => $model->getResourceType(),
                'id' => strval($model->getKey()),
                'attributes' => $attributes
            ];
            array_push($collection, $resource);
        }

        Assert::assertResourceCollectionEqualsCollection($expected, $model->getResourceType(), $collection);
    }

    /**
     * @test
     * @dataProvider assertResourceCollectionEqualsCollectionFailedProvider
     */
    public function assertResourceCollectionEqualsCollectionFailed($expected, $resourceType, $collection, $failureMsg)
    {
        $this->setFailureException($failureMsg);

        Assert::assertResourceCollectionEqualsCollection($expected, $resourceType, $collection);
    }

    public function assertResourceCollectionEqualsCollectionFailedProvider()
    {
        $expected = new Collection();
        $collection = [];
        for ($i = 1; $i < 3; $i++) {
            $attributes = [
                'TST_ID' => 10 + $i,
                'TST_NAME' => 'test' . $i,
                'TST_NUMBER' => 1000 * $i + 123,
                'TST_CREATION_DATE' => null
            ];

            $model = new ModelForTest();
            $model->setRawAttributes($attributes);
            $expected->push($model);

            $resource = [
                'type' => $model->getResourceType(),
                'id' => $model->getKey(),
                'attributes' => $attributes
            ];

            if ($i == 2) {
                $resource['attributes']['TST_NAME'] = 'wrong';
            }
            array_push($collection, $resource);
        }

        $resourceType = $model->getResourceType();

        return [
            'collection is not an array of objects' => [
                $expected,
                $resourceType,
                [
                    'key' => 'not an array ofg objects'
                ],
                Messages::MUST_BE_ARRAY_OF_OBJECTS
            ],
            'not the same counts' => [
                $expected,
                $resourceType,
                [
                    $collection[0]
                ],
                null
            ],
            'collections are not equal' => [
                $expected,
                $resourceType,
                $collection,
                null
            ]
        ];
    }

    // /**
    //  * @test
    //  */
    // public function assertResourceCollectionEqualsCollectionInvalidArguments()
    // {
    //     $attributes = [
    //         'TST_ID' => 10,
    //         'TST_NAME' => 'test',
    //         'TST_NUMBER' => 123,
    //         'TST_CREATION_DATE' => null
    //     ];

    //     $model = $attributes;
    //     $resourceType = 'test';
    //     $resource = [
    //         'type' => $resourceType,
    //         'id' => 10,
    //         'attributes' => $attributes
    //     ];

    //     $this->setInvalidArgumentException(1, Collection::class, [$model]);

    //     Assert::assertResourceCollectionEqualsCollection([$model], $resourceType, [$resource]);
    // }
}
