<?php

namespace VGirol\JsonApi\Tools\Assert;

use Illuminate\Http\JsonResponse;
use PHPUnit\Framework\Assert as PHPUnit;

trait JsonApiAssertCrud
{

    private static $responseParams;

    protected static function resetParams()
    {
        self::$responseParams = [
            'colCount' => null,
            'pageCount' => null,
            'page' => null,
            'itemPerPage' => null,
            'dataCount' => null,
            'resourceType' => null
        ];
    }

    protected static function setParam($key, $value)
    {
        self::$responseParams[$key] = $value;
        self::setPageCount();
        self::setDataCount();
    }

    protected static function setParams($arr)
    {
        self::$responseParams = array_merge(self::$responseParams, $arr);
        self::setPageCount();
        self::setDataCount();
    }

    protected static function getParam($key)
    {
        return self::$responseParams[$key];
    }

    public static function assertEmptyResourceObjectList($data)
    {
        PHPUnit::assertIsArray($data);
        PHPUnit::assertEmpty($data);
    }

    public static function assertValidResourceObjectList($data, $collection)
    {
        $dataCount = self::getParam('dataCount');
        $page = self::getParam('page');
        $itemPerPage = self::getParam('itemPerPage');

        static::assertIsArrayOfObjects($data);
        PHPUnit::assertEquals($dataCount, count($data));

        $dataIndex = mt_rand(0, $dataCount - 1);
        $colIndex = ($page - 1) * $itemPerPage + $dataIndex;
        static::assertValidResourceObject($data[$dataIndex], $collection[$colIndex]);
    }

    public static function assertValidLinksObject($links)
    {
        $number_parameter = config('json-api-paginate.number_parameter');
        $size_parameter = config('json-api-paginate.size_parameter');
        $itemPerPage = self::getParam('itemPerPage');
        $pageCount = self::getParam('pageCount');
        $page = self::getParam('page');
        $resourceType = self::getParam('resourceType');

        $expected = [
            'first' => route($resourceType . '.index', ["page[{$number_parameter}]" => 1, "page[{$size_parameter}]" => $itemPerPage]),
            'last' => route($resourceType . '.index', ["page[{$number_parameter}]" => $pageCount, "page[{$size_parameter}]" => $itemPerPage]),
            'prev' => route($resourceType . '.index', ["page[{$number_parameter}]" => $page - 1, "page[{$size_parameter}]" => $itemPerPage]),
            'next' => route($resourceType . '.index', ["page[{$number_parameter}]" => $page + 1, "page[{$size_parameter}]" => $itemPerPage])
        ];

        foreach ($expected as $key => $value) {
            PHPUnit::assertArrayHasKey($key, $links);
            if (!is_null($links[$key])) {
                PHPUnit::assertStringContainsString($value, $links[$key]);
            }
        }
        // PHPUnit::assertArraySubset($expected, $links);
    }

    public static function assertValidMetaObject($meta)
    {
        $expected = [
            'pagination' => [
                'total_items' => self::getParam('colCount'),
                'item_per_page' => self::getParam('itemPerPage'),
                'page_count' => self::getParam('pageCount'),
                'page' => self::getParam('page')
            ]
        ];

        PHPUnit::assertArraySubset($expected, $meta);
    }

    public static function assertValidErrorObject($error, $statusCode)
    {
        PHPUnit::assertEquals(strval($statusCode), $error['status']);
        PHPUnit::assertEquals(JsonResponse::$statusTexts[$statusCode], $error['title']);
        static::assertHasMember($error, 'details');
        if (config('app.debug')) {
            static::assertHasMeta($error);
        }
    }

    private static function setPageCount()
    {
        $colCount = self::getParam('colCount');
        $itemPerPage = self::getParam('itemPerPage');
        if (is_null($itemPerPage) || ($itemPerPage == 0)) {
            $pageCount = 1;
        } else {
            $pageCount = intdiv($colCount, $itemPerPage);
            if ($colCount % $itemPerPage != 0) {
                $pageCount++;
            }
        }

        self::$responseParams['pageCount'] = $pageCount;
    }

    private static function setDataCount()
    {
        $pageCount = self::getParam('pageCount');
        if (is_null($pageCount)) {
            return;
        }

        $page = self::getParam('page');
        $colCount = self::getParam('colCount');
        $itemPerPage = self::getParam('itemPerPage');
        if ($pageCount > 1) {
            if ($page == $pageCount) {
                $dataCount = $colCount - ($page - 1) * $itemPerPage;
            } else {
                $dataCount = $itemPerPage;
            }
        } else {
            $dataCount = $colCount;
        }

        self::$responseParams['dataCount'] = $dataCount;
    }
}
