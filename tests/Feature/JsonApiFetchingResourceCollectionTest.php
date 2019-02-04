<?php
namespace VGirol\JsonApi\Tests\Feature\Response;

use PHPUnit\Framework\Assert as PHPUnit;

trait JsonApiFetchingResourceCollectionTest
{
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

        // Sets test params
        $this->setParams([
            'page' => 1,
            'itemPerPage' => config('json-api-paginate.max_results'),
            'colCount' => $collection->count(),
            'resourceType' => $this->resourceType
        ]);

        // Sends request and gets response
        $response = $this->json('GET', $this->endpoint);

        // Check response status code
        $response->assertStatus(200);

        // Decode JSON response
        $json = $response->json();

        // Checks response structure
        $this->checkJsonApiStructure($json);

        // Checks data member
        $this->assertHasData($json);
        $data = $json['data'];
        $this->assertEmptyResourceObjectList($data, $collection);

        // Checks links member
        $this->assertHasLinks($json);
        $links = $json['links'];
        $this->assertValidLinksObject($links);

        // Checks meta member
        $this->assertHasMeta($json);
        $meta = $json['meta'];
        $this->assertValidMetaObject($meta);
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
        $collection = factoryJsonapi($this->model, $count)->create();
        $collection = $collection->sortBy('TST_ID')->values();

        // Sets test params
        $this->setParams([
            'page' => 1,
            'itemPerPage' => $itemPerPage,
            'colCount' => $collection->count(),
            'resourceType' => $this->resourceType
        ]);

        // Sends request and gets response
        $response = $this->json('GET', $this->endpoint);

        // Check response status code
        $response->assertStatus(200);

        // Decode JSON response
        $json = $response->json();

        // Checks response structure
        $this->checkJsonApiStructure($json);

        // Checks data member
        $this->assertHasData($json);
        $data = $json['data'];
        $this->assertValidResourceObjectList($data, $collection);

        // Checks links member
        $this->assertHasLinks($json);
        $links = $json['links'];
        $this->assertValidLinksObject($links);

        // Checks meta member
        $this->assertHasMeta($json);
        $meta = $json['meta'];
        $this->assertValidMetaObject($meta);
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
        $collection = factoryJsonapi($this->model, $count)->create();
        $collection = $collection->sortBy('TST_ID')->values();

        // Sets test params
        $this->setParams([
            'page' => $page,
            'itemPerPage' => $itemPerPage,
            'colCount' => $collection->count(),
            'resourceType' => $this->resourceType
        ]);

        // Sends request and gets response
        $response = $this->json('GET', "{$this->endpoint}?page[{$number_parameter}]={$page}&page[{$size_parameter}]={$itemPerPage}");

        // Check response status code
        $response->assertStatus(200);

        // Decode JSON response
        $json = $response->json();

        // Checks response structure
        $this->checkJsonApiStructure($json);

        // Checks data member
        $this->assertHasData($json);
        $data = $json['data'];
        $this->assertValidResourceObjectList($data, $collection);

        // Checks links member
        $this->assertHasLinks($json);
        $links = $json['links'];
        $this->assertValidLinksObject($links);

        // Checks meta member
        $this->assertHasMeta($json);
        $meta = $json['meta'];
        $this->assertValidMetaObject($meta);
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
        $this->checkJsonApiStructure($json);

        // Check errors member
        $this->assertHasErrors($json);
        $errors = $json['errors'];
        PHPUnit::assertCount(1, $errors);
        $this->assertValidErrorObject($errors[0], 404);
    }

    /**
     * GET /endpoint/{id}/relationships/{relationship}
     * Should return 200 with resource object list
     *
     * @return void
     */
    // public function testFetchRelationships()
    // {
    //     // Creates collection
    //     $itemPerPage = config('json-api-paginate.max_results');
    //     $count = mt_rand(1, round($itemPerPage / 2));
    //     $collection = factoryJsonapi($this->model, $count)->create();
    //     $collection = $collection->sortBy('TST_ID')->values();

    //     // Sets test params
    //     $this->setParams([
    //         'page' => 1,
    //         'itemPerPage' => $itemPerPage,
    //         'colCount' => $collection->count(),
    //         'resourceType' => $this->resourceType
    //     ]);

    //     // Sends request and gets response
    //     $response = $this->json('GET', $this->endpoint);

    //     // Check response status code
    //     $response->assertStatus(200);

    //     // Decode JSON response
    //     $json = $response->json();

    //     // Checks response structure
    //     $this->checkJsonApiStructure($json);

    //     // Checks data member
    //     $this->assertHasData($json);
    //     $data = $json['data'];
    //     $this->assertValidResourceObjectList($data, $collection);

    //     // Checks links member
    //     $this->assertHasLinks($json);
    //     $links = $json['links'];
    //     $this->assertValidLinksObject($links);

    //     // Checks meta member
    //     $this->assertHasMeta($json);
    //     $meta = $json['meta'];
    //     $this->assertValidMetaObject($meta);
    // }

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
    //         ->assertIsResourceObjectList($relationships, $relatedCount)
    //         ->assertResourceObjectIsValid(
    //             $relationships[mt_rand(0, $relatedCount - 1)],
    //             $this->getRelationshipsParams()
    //         );
    // }
}
