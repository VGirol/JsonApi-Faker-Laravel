<?php

namespace VGirol\JsonApiAssert\Laravel\Asserts;

use PHPUnit\Framework\Assert as PHPUnit;
use VGirol\JsonApiAssert\Assert as JsonApiAssert;

trait AssertStructure
{
    /**
     * Undocumented function
     *
     * @param Illuminate\Database\Eloquent\Model $model
     * @param string $resourceType
     * @param array $resource
     * @return void
     */
    public static function assertResourceIdentifierObjectEqualsModel($model, $resourceType, $resource)
    {
        JsonApiAssert::assertIsNotArrayOfObjects($resource);

        PHPUnit::assertEquals(
            $resourceType,
            $resource['type']
        );

        PHPUnit::assertEquals(
            $model->getKey(),
            $resource['id']
        );
    }

    /**
     * Undocumented function
     *
     * @param Illuminate\Database\Eloquent\Model $model
     * @param string $resourceType
     * @param array $resource
     * @return void
     */
    public static function assertResourceObjectEqualsModel($model, $resourceType, $resource)
    {
        static::assertResourceIdentifierObjectEqualsModel($model, $resourceType, $resource);

        JsonApiAssert::assertHasAttributes($resource);
        PHPUnit::assertEquals(
            $model->getAttributes(),
            $resource['attributes']
        );
    }

    /**
     * Undocumented function
     *
     * @param Illuminate\Database\Eloquent\Collection $collection
     * @param array $data
     * @param array $options
     * @return void
     */
    public static function assertResourceObjectListEqualsCollection($collection, $data, $options)
    {
        $options = static::mergeOptionsWithDefault($options);

        JsonApiAssert::assertIsArrayOfObjects($data);
        PHPUnit::assertEquals($options['dataCount'], count($data));

        list($dataIndex, $colIndex) = static::getListOfIndex($options);
        for ($i = 0; $i < count($dataIndex); $i++) {
            static::assertResourceObjectEqualsModel($collection[$colIndex[$i]], $options['resourceType'], $data[$dataIndex[$i]]);
        }
    }

    /**
     * Undocumented function
     *
     * @param Illuminate\Database\Eloquent\Model|null $model
     * @param string|null $resourceType
     * @param [type] $resLinkage
     * @return void
     */
    public static function assertSingleResourceLinkageEquals($model, $resourceType, $resLinkage)
    {
        if (is_null($model)) {
            PHPUnit::assertNull($resLinkage);
        } else {
            JsonApiAssert::assertIsValidResourceLinkage($resLinkage);
            JsonApiAssert::assertIsNotArrayOfObjects($resLinkage);
            static::assertResourceIdentifierObjectEqualsModel($model, $resourceType, $resLinkage);
        }
    }

    public static function assertResponseResourceLinkageListEqualsCollection($collection, $data, $options)
    {
        $options = static::mergeOptionsWithDefault($options);

        JsonApiAssert::assertIsValidResourceLinkage($data);
        JsonApiAssert::assertIsArrayOfObjects($data);

        list($dataIndex, $colIndex) = static::getListOfIndex($options);
        for ($i = 0; $i < count($dataIndex); $i++) {
            static::assertResourceIdentifierObjectEqualsModel($collection[$colIndex[$i]], $options['resourceType'], $data[$dataIndex[$i]]);
        }
    }

     public static function getJsonFromPath($json, $path)
    {
        $path = explode('.', $path);
        foreach ($path as $member) {
            JsonApiAssert::assertHasMember($json, $member);
            $json = $json[$member];
        }

        return $json;
    }

    public static function mergeOptionsWithDefault($options = [])
    {
        foreach (static::getDefaultOptions() as $key => $value) {
            if (!isset($options[$key])) {
                $options[$key] = $value;
            }
        }

        $options['pageCount'] = self::getPageCount($options['colCount'], $options['itemPerPage']);
        $options['dataCount'] = self::getDataCount($options['pageCount'], $options['page'], $options['colCount'], $options['itemPerPage']);

        return $options;
    }

    private static function getDefaultOptions()
    {
        return [
            'colCount' => null,
            'dataCount' => null,
            'pageCount' => null,
            'page' => 1,
            'itemPerPage' => config('json-api-paginate.max_results'),
            'resourceType' => null,
            'check-all' => true
        ];
    }

    private static function getPageCount($colCount, $itemPerPage)
    {
        if (is_null($itemPerPage) || ($itemPerPage == 0)) {
            $pageCount = 1;
        } else {
            $pageCount = intdiv($colCount, $itemPerPage);
            if ($colCount % $itemPerPage != 0) {
                $pageCount++;
            }
        }

        return $pageCount;
    }

    private static function getDataCount($pageCount, $page, $colCount, $itemPerPage)
    {
        if (is_null($pageCount)) {
            return null;
        }

        if ($pageCount > 1) {
            if ($page == $pageCount) {
                $dataCount = $colCount - ($page - 1) * $itemPerPage;
            } else {
                $dataCount = $itemPerPage;
            }
        } else {
            $dataCount = $colCount;
        }

        return $dataCount;
    }

    private static function getListOfIndex($options)
    {
        if ($options['colCount'] == 0 || $options['dataCount'] == 0) {
            return [[], []];
        }

        if ($options['check-all']) {
            $min = ($options['page'] - 1) * $options['itemPerPage'];
            if ($options['pageCount'] > 1) {
                if ($options['page'] == $options['pageCount']) {
                    $nb = $options['colCount'] - ($options['page'] - 1) * $options['itemPerPage'];
                } else {
                    $nb = $options['itemPerPage'];
                }
            } else {
                $nb = $options['colCount'];
            }
            $dataIndex = range(0, $nb - 1, 1);
            $colIndex = range($min, $min + $nb - 1, 1);
        } else {
            $dataIndex = [mt_rand(0, $options['dataCount'] - 1)];
            $colIndex = [($options['page'] - 1) * $options['itemPerPage'] + $dataIndex];
        }

        return [$dataIndex, $colIndex];
    }
}
