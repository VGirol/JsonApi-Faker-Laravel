<?php
namespace VGirol\JsonApiAssert\Laravel\Tests\Asserts\Structure;

use Illuminate\Database\Eloquent\Model;
use VGirol\JsonApiAssert\Laravel\Assert;
use VGirol\JsonApiAssert\Laravel\Tests\TestCase;
use VGirol\JsonApiAssert\Laravel\Tests\Tools\Models\ModelForTest;
use VGirol\JsonApiAssert\Messages;

class ResourceTest extends TestCase
{
    /**
     * @test
     */
    public function assertResourceObjectEquals()
    {
        $attributes = [
            'TST_ID' => 10,
            'TST_NAME' => 'test',
            'TST_NUMBER' => 123,
            'TST_CREATION_DATE' => null
        ];

        $model = new ModelForTest();
        $model->setRawAttributes($attributes);

        $resource = [
            'type' => $model->getResourceType(),
            'id' => $model->getKey(),
            'attributes' => $attributes
        ];

        Assert::assertResourceObjectEquals($model, $model->getResourceType(), $resource);
    }

    /**
     * @test
     * @dataProvider assertResourceObjectEqualsFailedProvider
     */
    public function assertResourceObjectEqualsFailed($model, $resourceType, $resource, $failureMsg)
    {
        $this->setFailureException($failureMsg);

        Assert::assertResourceObjectEquals($model, $resourceType, $resource);
    }

    public function assertResourceObjectEqualsFailedProvider()
    {
        $attributes = [
            'TST_ID' => 10,
            'TST_NAME' => 'test',
            'TST_NUMBER' => 123,
            'TST_CREATION_DATE' => null
        ];

        $model = new ModelForTest();
        $model->setRawAttributes($attributes);

        $resourceType = $model->getResourceType();

        return [
            'not same type' => [
                $model,
                $resourceType,
                [
                    'type' => 'wrong',
                    'id' => $model->getKey(),
                    'attributes' => $attributes
                ],
                null
            ],
            'no attributes member' => [
                $model,
                $resourceType,
                [
                    'type' => $resourceType,
                    'id' => $model->getKey()
                ],
                sprintf(Messages::HAS_MEMBER, 'attributes')
            ],
            'attributes member has wrong value' => [
                $model,
                $resourceType,
                [
                    'type' => $resourceType,
                    'id' => $model->getKey(),
                    'attributes' => [
                        'TST_ID' => 10,
                        'TST_NAME' => 'wrong value',
                        'TST_NUMBER' => 123,
                        'TST_CREATION_DATE' => null
                    ]
                ],
                null
            ]
        ];
    }

    /**
     * @test
     */
    public function assertResourceObjectEqualsInvalidArguments()
    {
        $attributes = [
            'TST_ID' => 10,
            'TST_NAME' => 'test',
            'TST_NUMBER' => 123,
            'TST_CREATION_DATE' => null
        ];

        $model = $attributes;
        $resourceType = 'test';
        $resource = [
            'type' => $resourceType,
            'id' => 10,
            'attributes' => $attributes
        ];

        $this->setInvalidArgumentException(1, Model::class, $model);

        Assert::assertResourceObjectEquals($model, $resourceType, $resource);
    }
}
