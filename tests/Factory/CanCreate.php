<?php
namespace VGirol\JsonApiAssert\Laravel\Tests\Factory;

use VGirol\JsonApiAssert\Laravel\Tests\Tools\Models\ModelForTest;

trait CanCreate
{
    public function modelFactory($type, $isRI, $i = 0)
    {
        $id = 100 + $i * 10;
        $attributes = [
            'TST_ID' => $id,
            'TST_NAME' => 'model #' . $i,
            'TST_NUMBER' => $i * 100 + $i * 5,
            'TST_CREATION_DATE' => '2019-01-0' . $i
        ];

        $expectedModel = [
            'type' => $type,
            'id' => strval($id)
        ];
        if (!$isRI) {
            $expectedModel['attributes'] = $attributes;
        }

        $model = new ModelForTest;
        $model->setRawAttributes($attributes);

        return [$model, $expectedModel];
    }

    protected function collectionFactory($count, $type, $isRI)
    {
        $expected = [];
        $collection = [];
        for ($i = 1; $i <= $count; $i++) {
            list($model, $expectedModel) = $this->modelFactory($type, $isRI, $i);
            array_push($expected, $expectedModel);
            array_push($collection, $model);
        }

        return [collect($collection), $expected];
    }
}
