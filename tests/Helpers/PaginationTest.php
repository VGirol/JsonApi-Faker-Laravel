<?php

namespace VGirol\JsonApiFaker\Laravel\Tests\Helpers;

use PHPUnit\Framework\Assert as PHPUnit;
use VGirol\JsonApiFaker\Laravel\Helpers\Pagination;
use VGirol\JsonApiFaker\Laravel\Tests\TestCase;

class PaginationTest extends TestCase
{
    /**
     * @test
     */
    public function getOptions()
    {
        $options = Pagination::getOptions();

        $expected = [
            'itemCount'       => null,
            'pageCount'       => 1,
            'page'            => 1,
            'itemPerPage'     => null,
            'routeParameters' => [],
        ];

        PHPUnit::assertIsArray($options);
        PHPUnit::assertEquals($expected, $options);
    }

    /**
     * @test
     * @dataProvider getOptionsWithPrefilledValuesProvider
     */
    public function getOptionsWithPrefilledValues($preFilled, $expected)
    {
        $options = Pagination::getOptions($preFilled);

        PHPUnit::assertIsArray($options);
        PHPUnit::assertEquals($expected, $options);
    }

    public function getOptionsWithPrefilledValuesProvider()
    {
        return [
            'Pre filled values' => [
                [
                    'itemCount'   => 14,
                    'itemPerPage' => 5,
                    'page'        => 2,
                ],
                [
                    'itemCount'       => 14,
                    'pageCount'       => 3,
                    'page'            => 2,
                    'itemPerPage'     => 5,
                    'routeParameters' => [],
                ],
            ],
            'No items per page' => [
                [
                    'itemCount'   => 14,
                    'itemPerPage' => null,
                    'page'        => 2,
                ],
                [
                    'itemCount'       => 14,
                    'pageCount'       => 1,
                    'page'            => 2,
                    'itemPerPage'     => null,
                    'routeParameters' => [],
                ],
            ],
        ];
    }

    /**
     * @test
     * @dataProvider sliceCollectionProvider
     */
    public function sliceCollection($itemCount, $itemPerPage, $page)
    {
        $options = Pagination::getOptions([
            'itemCount'   => $itemCount,
            'itemPerPage' => $itemPerPage,
            'page'        => $page,
        ]);

        $collection = collect(range(1, $itemCount));

        $expected = $collection->slice(
            ($page - 1) * $itemPerPage,
            $itemPerPage
        )->values();
        $result = Pagination::sliceCollection($collection, $options);

        PHPUnit::assertEquals($expected, $result);
    }

    public function sliceCollectionProvider()
    {
        return [
            'No particularity' => [
                14,
                5,
                2,
            ],
            'Only one page' => [
                3,
                5,
                1,
            ],
            'Last page' => [
                5,
                3,
                2,
            ],
        ];
    }

    /**
     * @test
     */
    public function sliceEmptyCollection()
    {
        $maxResult = 5;
        $options = Pagination::getOptions([
            'itemPerPage' => $maxResult,
        ]);
        $collection = collect([]);

        $result = Pagination::sliceCollection($collection, $options);

        PHPUnit::assertSame($collection, $result);
    }

    /**
     * @test
     */
    public function sliceCollectionWithPageOutOfRange()
    {
        $maxResult = 5;
        $itemCount = 14;
        $page = 7;
        $options = Pagination::getOptions([
            'itemPerPage' => $maxResult,
            'itemCount'   => $itemCount,
            'page'        => $page,
        ]);

        $collection = collect(range(1, $itemCount));

        $expected = $collection->slice(
            0,
            $maxResult
        )->values();
        $result = Pagination::sliceCollection($collection, $options);

        PHPUnit::assertEquals($expected, $result);
    }
}
