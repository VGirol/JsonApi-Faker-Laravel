<?php
namespace VGirol\JsonApi\Tests\Feature\Response;

use VGirol\JsonApi\Tests\TestCase;
use PHPUnit\Framework\Assert as PHPUnit;

class JsonApiFetchingRelationshipsTest extends TestCase
{
    use Common;

    /**
     * GET /endpoint/{id}/relationships/{relationship}
     * Should return 200 with single resource linkage object
     *
     * @return void
     */
    public function testFetchToOneRelationships()
    {
        // Creates an object with filled out fields
        $model = factoryJsonapi($this->getModelClassName())->create();

        // Creates collection
        $related = factoryJsonapi($this->getModelClassName('related'))->create(['TST_ID' => $model->getKey()]);

        // Sends request and gets response
        $url = route("{$this->routeName}.relationships", ['id' => $model->getKey(), 'relationship' => 'relatedToOne']);
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
        $this->assertResponseSingleResourceLinkageEquals($related, $data);

        // Checks links member
        $this->assertResponseLinksObjectSubset(
            [
                'self' => $url
            ],
            $json
        );
    }

    /**
     * GET /endpoint/{id}/relationships/{relationship}
     * Should return 200 with array of resource linkage object
     *
     * @return void
     */
    public function testFetchToManyRelationships()
    {
        // Creates an object with filled out fields
        $model = factoryJsonapi($this->getModelClassName())->create();

        // Creates collection
        $itemPerPage = config('json-api-paginate.max_results');
        $count = mt_rand(1, round($itemPerPage / 2));
        $collection = factoryJsonapi($this->getModelClassName('related'), $count)->create(['TST_ID' => $model->getKey()]);
        $collection = $collection->sortBy('REL_ID')->values();

        // Sets test params
        $options = [
            'page' => 1,
            'itemPerPage' => $itemPerPage,
            'colCount' => $collection->count(),
            'resourceType' => 'related'
        ];

        // Sends request and gets response
        $url = route("{$this->routeName}.relationships", ['id' => $model->getKey(), 'relationship' => 'relatedToMany']);
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
        $this->assertResponseResourceLinkageListEqualsCollection($collection, $data, $options);

        // Checks links member
        $this->assertResponseLinksObjectSubset(
            [
                'self' => $url
            ],
            $json
        );
    }

    /**
     * GET /endpoint/{id}/relationships/{relationship}
     * Should return 200 with null
     *
     * @return void
     */
    public function testFetchToOneEmptyRelationships()
    {
        // Creates an object with filled out fields
        $model = factoryJsonapi($this->getModelClassName())->create();

        // Sends request and gets response
        $url = route("{$this->routeName}.relationships", ['id' => $model->getKey(), 'relationship' => 'relatedToOne']);
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
        PHPUnit::assertNull($data);

        // Checks links member
        $this->assertResponseLinksObjectSubset(
            [
                'self' => $url
            ],
            $json
        );
    }

    /**
     * GET /endpoint/{id}/relationships/{relationship}
     * Should return 200 with empty array
     *
     * @return void
     */
    public function testFetchToManyEmptyRelationships()
    {
        // Creates an object with filled out fields
        $model = factoryJsonapi($this->getModelClassName())->create();

        // Sends request and gets response
        $url = route("{$this->routeName}.relationships", ['id' => $model->getKey(), 'relationship' => 'relatedToMany']);
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
        $this->assertResponseLinksObjectSubset(
            [
                'self' => $url
            ],
            $json
        );
    }

    /**
     * GET /endpoint/{id}/relationships/{relationship}
     * Should return 404
     *
     * @return void
     */
    public function testFetchRelationshipsThatDoesNotExists()
    {
        // Sends request and gets response
        $url = route("{$this->routeName}.relationships", ['id' => 666, 'relationship' => 'relatedToOne']);
        $response = $this->json('GET', $url);

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
}
