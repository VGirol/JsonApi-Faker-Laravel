<?php

namespace VGirol\JsonApiFaker\Laravel\Helpers;

use Illuminate\Support\Collection;

class Pagination
{
    /**
     * Get all the options needed for pagination
     *
     * Returns an array with these keys :
     * - itemCount (integer)
     * - pageCount (integer)
     * - page (integer)
     * - itemPerPage (integer)
     * - routeParameters (array)
     *
     * @param array $options
     * @return array
     */
    public static function getOptions($options = []): array
    {
        foreach (static::getDefaultOptions() as $key => $value) {
            if (!isset($options[$key])) {
                $options[$key] = $value;
            }
        }

        $options['pageCount'] = self::getPageCount($options['itemCount'], $options['itemPerPage']);

        return $options;
    }

    /**
     * Slice the given collection according to the options passed as second argument
     *
     * @param Collection $collection
     * @param array $options
     * @return Collection
     */
    public static function sliceCollection($collection, $options)
    {
        if ($options['itemCount'] == 0) {
            return $collection;
        }

        $start = ($options['page'] - 1) * $options['itemPerPage'];
        if ($start > $options['itemCount']) {
            $start = 0;
        }

        return $collection->slice(
            $start,
            static::getItemCount($options)
        )->values();
    }

    private static function getDefaultOptions()
    {
        return [
            'itemCount' => null,
            'pageCount' => null,
            'page' => 1,
            'itemPerPage' => null,
            'routeParameters' => []
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
            return $options['itemCount'];
        }

        if ($options['page'] == $options['pageCount']) {
            return $options['itemCount'] - ($options['page'] - 1) * $options['itemPerPage'];
        }

        return $options['itemPerPage'];
    }
}
