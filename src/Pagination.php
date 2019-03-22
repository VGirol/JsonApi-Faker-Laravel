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

    public static function extractPage($collection, $options)
    {
        if ($options['colCount'] == 0) {
            return $collection;
        }

        $min = ($options['page'] - 1) * $options['itemPerPage'];
        $nb = static::getItemCount($options);

        return $collection->slice($min, $nb);
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
            $pageCount = 1;
        } else {
            $pageCount = intdiv($colCount, $itemPerPage);
            if ($colCount % $itemPerPage != 0) {
                $pageCount++;
            }
            if ($pageCount == 0) {
                $pageCount = 1;
            }
        }

        return $pageCount;
    }

    private static function getItemCount($options)
    {
        if ($options['pageCount'] > 1) {
            if ($options['page'] == $options['pageCount']) {
                $nb = $options['colCount'] - ($options['page'] - 1) * $options['itemPerPage'];
            } else {
                $nb = $options['itemPerPage'];
            }
        } else {
            $nb = $options['colCount'];
        }

        return $nb;
    }
}
