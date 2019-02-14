<?php
namespace VGirol\JsonApi\Tests\Feature\Response;

use VGirol\JsonApi\Tests\TestCase;
use PHPUnit\Framework\Assert as PHPUnit;

class JsonApiFetchingResourceCollectionTest extends TestCase
{
    use Common;

    protected function getMetaPaginationSubset($options)
    {
        $options = static::mergeOptionsWithDefault($options);

        return [
            'pagination' => [
                'total_items' => $options['colCount'],
                'item_per_page' => $options['itemPerPage'],
                'page_count' => $options['pageCount'],
                'page' => $options['page']
            ]
        ];
    }

    protected function getLinksForPagination($options, $prev = false, $next = false)
    {
        $options = static::mergeOptionsWithDefault($options);

        return [
            'first' => $this->linkFactory($options['resourceType'] . '.index', 1, $options['itemPerPage']),
            'last' => $this->linkFactory($options['resourceType'] . '.index', $options['pageCount'], $options['itemPerPage']),
            'prev' => $prev ? $this->linkFactory($options['resourceType'] . '.index', $options['page'] - 1, $options['itemPerPage']) : null,
            'next' => $next ? $this->linkFactory($options['resourceType'] . '.index', $options['page'] + 1, $options['itemPerPage']) : null
        ];
    }

    protected function linkFactory($routeName, $page, $itemPerPage)
    {
        $number_parameter = config('json-api-paginate.number_parameter');
        $size_parameter = config('json-api-paginate.size_parameter');

        return route($routeName, ["page[{$number_parameter}]" => $page, "page[{$size_parameter}]" => $itemPerPage]);
    }

    /**
     * GET /endpoint/
     * Should return 200 with empty data array
     *
     * @return void
     */
    public function testFetchEmptyResourceCollection()
    {
        // Creates collection
        $collection = collect([]);

        // Sets test options
        $options = [
            'colCount' => $collection->count(),
            'resourceType' => $this->getObjectResourceType()
        ];

        // Sends request and gets response
        $url = route("{$this->routeName}.index");
        $response = $this->json('GET', $url);

        // Check response status code
        $response->assertStatus(200);

        // Decode JSON response
        $json = $response->json();

        // Checks response structure
        $this->assertHasValidStructure($json);

        // Checks data member
        $this->assertHasData($json);
        $data = $json['data'];
        PHPUnit::assertIsArray($data);
        PHPUnit::assertEmpty($data);

        // Checks links member
        $this->assertResponseLinksObjectSubset($this->getLinksForPagination($options, false, false), $json);

        // Checks meta member
        $this->assertResponseMetaObjectSubset($this->getMetaPaginationSubset($options), $json);
    }

    /**
     * GET /endpoint/
     * Should return 200 with resource object list
     *
     * @return void
     */
    public function testFetchResourceCollection()
    {
        // Creates collection
        $itemPerPage = config('json-api-paginate.max_results');
        $count = mt_rand(1, round($itemPerPage / 2));
        $collection = factoryJsonapi($this->getModelClassName(), $count)->create();
        $collection = $collection->sortBy('TST_ID')->values();

        // Sets test options
        $options = [
            'page' => 1,
            'itemPerPage' => $itemPerPage,
            'colCount' => $collection->count(),
            'resourceType' => $this->getObjectResourceType()
        ];

        // Sends request and gets response
        $url = route("{$this->routeName}.index");
        $response = $this->json('GET', $url);

        // Check response status code
        $response->assertStatus(200);

        // Decode JSON response
        $json = $response->json();

        // Checks response structure
        $this->assertHasValidStructure($json);

        // Checks data member
        $this->assertHasData($json);
        $data = $json['data'];
        $this->assertResourceObjectListEqualsCollection($collection, $data, $options);

        // Checks links member
        $this->assertResponseLinksObjectSubset($this->getLinksForPagination($options, false, false), $json);

        // Checks meta member
        $this->assertResponseMetaObjectSubset($this->getMetaPaginationSubset($options), $json);
    }

    /**
     * GET /endpoint/
     * Should return 200 with resource object list
     *
     * @return void
     */
    public function testFetchResourceCollectionWithPagination()
    {
        // Creates collection
        $page = 3;
        $itemPerPage = 5;

        $number_parameter = config('json-api-paginate.number_parameter');
        $size_parameter = config('json-api-paginate.size_parameter');

        $count = mt_rand($page * $itemPerPage + 1, ($page + 2) * $itemPerPage);
        $collection = factoryJsonapi($this->getModelClassName(), $count)->create();
        $collection = $collection->sortBy('TST_ID')->values();

        // Sets test options
        $options = [
            'page' => $page,
            'itemPerPage' => $itemPerPage,
            'colCount' => $collection->count(),
            'resourceType' => $this->getObjectResourceType()
        ];

        // Sends request and gets response
        $url = route("{$this->routeName}.index", ["page[{$number_parameter}]" => $page, "page[{$size_parameter}]" => $itemPerPage]);
        $response = $this->json('GET', $url);

        // Check response status code
        $response->assertStatus(200);

        // Decode JSON response
        $json = $response->json();

        // Checks response structure
        $this->assertHasValidStructure($json);

        // Checks data member
        $this->assertHasData($json);
        $data = $json['data'];
        $this->assertResourceObjectListEqualsCollection($collection, $data, $options);

        // Checks links member
        $this->assertResponseLinksObjectSubset($this->getLinksForPagination($options, true, true), $json);

        // Checks meta member
        $this->assertResponseMetaObjectSubset($this->getMetaPaginationSubset($options), $json);
    }

    /**
     * GET /endpoint/
     * Should return 200 with sorted resource object list
     *
     * @return void
     */
    public function testFetchResourceCollectionWithSort()
    {
        // Sort options
        $sortField = 'TST_NAME';
        $sortDir = '+';

        // Creates collection
        $itemPerPage = config('json-api-paginate.max_results');
        $count = mt_rand(1, round($itemPerPage / 2));
        $collection = factoryJsonapi($this->getModelClassName(), $count)->create();
        if ($sortDir == '+') {
            $collection = $collection->sortBy($sortField)->values();
        } else {
            $collection = $collection->sortByDesc($sortField)->values();
        }

        // Sets test params
        // Sets test options
        $options = [
            'page' => 1,
            'itemPerPage' => $itemPerPage,
            'colCount' => $collection->count(),
            'resourceType' => $this->getObjectResourceType()
        ];

        // Sends request and gets response
        $url = route("{$this->routeName}.index", ['sort' => "{$sortDir}{$sortField}"]);
        $response = $this->json('GET', $url);

        // Check response status code
        $response->assertStatus(200);

        // Decode JSON response
        $json = $response->json();

        // Checks response structure
        $this->assertHasValidStructure($json);

        // Checks data member
        $this->assertHasData($json);
        $data = $json['data'];
        $this->assertResourceObjectListEqualsCollection($collection, $data, $options);

        // Checks links member
        $expected = $this->getLinksForPagination($options, false, false);
        array_walk($expected, function (&$value, $key, $queryParam) {
            if (!is_null($value)) {
                $value .= $queryParam;
            }
        }, "&sort={$sortDir}{$sortField}");
        $this->assertResponseLinksObjectSubset($expected, $json);

        // Checks meta member
        $this->assertResponseMetaObjectSubset($this->getMetaPaginationSubset($options), $json);
    }

    /**
     * GET /endpoint/
     * Should return 404
     *
     * @return void
     */
    public function testFetchResourceCollectionThatDoesNotExist()
    {
        // Sends request and gets response
        $response = $this->json('GET', 'badEndPoint');

        // Check response status code
        $response->assertStatus(404);

        // Decode JSON response
        $json = $response->json();

        // Checks response structure
        $this->assertHasValidStructure($json);

        // Check errors member
        $this->assertHasErrors($json);
        $errors = $json['errors'];
        PHPUnit::assertCount(1, $errors);
        $this->assertResponseErrorObjectEquals($errors[0], 404);
    }

    /**
     * GET /endpoint/
     * Should return 200 with empty data array
     *
     * @return void
     */
    // public function testListWithIncludedRelationship()
    // {
    //     $max_result = config('json-api-paginate.max_results');
    //     $count = mt_rand(1, $max_result);
    //     $relatedCount = 5;

    //     $collection = $this->createModelWithRelationships([
    //         'model_count' => $count,
    //         'related_count' => $relatedCount
    //     ]);

    //     $response = $this->json('GET', "{$this->endpoint}?include={$this->include}");

    //     $response->assertStatus(200);

    //     $json = $response->json();
    //     $this
    //         ->checkJsonResponseStructure($json)
    //         ->assertHasData($json);

    //     $data = $json['data'];
    //     $resource = $data[mt_rand(0, $count - 1)];
    //     $this->assertHasRelationships($resource);

    //     $relationships = $resource['relationships'];
    //     $this
    //         ->assertIsValidResourceObjectList($relationships, $relatedCount)
    //         ->assertResourceObjectIsValid(
    //             $relationships[mt_rand(0, $relatedCount - 1)],
    //             $this->getRelationshipsParams()
    //         );
    // }
}
