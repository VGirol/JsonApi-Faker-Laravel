<?php

namespace VGirol\JsonApiAssert\Laravel;

class Pagination
{
    public static function getOptions($options = [])
    {
        foreach (static::getDefaultOptions() as $key => $value) {
            if (!isset($options[$key])) {
                $options[$key] = $value;
            }
        }

        $options['pageCount'] = self::getPageCount($options['colCount'], $options['itemPerPage']);

        return $options;
    }

    public static function sliceCollection($collection, $options)
    {
        if ($options['colCount'] == 0) {
            return $collection;
        }

        return $collection->slice(
            ($options['page'] - 1) * $options['itemPerPage'],
            static::getItemCount($options)
        );
    }

    private static function getDefaultOptions()
    {
        return [
            'colCount' => null,
            'pageCount' => null,
            'page' => 1,
            'itemPerPage' => config('json-api-paginate.max_results')
        ];
    }

    private static function getPageCount($colCount, $itemPerPage)
    {
        if (is_null($itemPerPage) || ($itemPerPage == 0)) {
            return 1;
        }

        $pageCount = intdiv($colCount, $itemPerPage);
        if ($colCount % $itemPerPage != 0) {
            $pageCount++;
        }
        if ($pageCount == 0) {
            $pageCount = 1;
        }

        return $pageCount;
    }

    private static function getItemCount($options)
    {
        if ($options['pageCount'] <= 1) {
            return $options['colCount'];
        }

        if ($options['page'] == $options['pageCount']) {
            return $options['colCount'] - ($options['page'] - 1) * $options['itemPerPage'];
        }

        return $options['itemPerPage'];
    }
}
