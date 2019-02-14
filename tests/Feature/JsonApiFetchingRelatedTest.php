<?php
namespace VGirol\JsonApi\Tests\Feature\Response;

use VGirol\JsonApi\Tests\TestCase;
use PHPUnit\Framework\Assert as PHPUnit;

class JsonApiFetchingRelatedTest extends TestCase
{
    use Common;

    /**
     * GET /endpoint/<id>
     * Should return 200 with data
     *
     * @return void
     */
    public function testFetchRelatedAsSingleResource()
    {
        // Creates an object with filled out fields
        $model = factoryJsonapi($this->getModelClassName())->create();

        // Creates collection
        $related = factoryJsonapi($this->getModelClassName('related'))->create(['TST_ID' => $model->getKey()]);

        // Sends request and gets response
        $url = route("{$this->routeName}.related.show", ['id' => $model->getKey(), 'relationship' => 'relatedToOne']);
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
        $this->assertResourceObjectEqualsModel($related, $data);
    }

    /**
     * GET /endpoint/<id>
     * Should return 200 with null
     *
     * @return void
     */
    public function testFetchRelatedAsEmptySingleResource()
    {
        // Creates an object with filled out fields
        $model = factoryJsonapi($this->getModelClassName())->create();

        // Sends request and gets response
        $url = route("{$this->routeName}.related.show", ['id' => $model->getKey(), 'relationship' => 'relatedToOne']);
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
    }

    /**
     * GET /endpoint/<id>
     * Should return 200 with data
     *
     * @return void
     */
    public function testFetchRelatedAsResourceCollection()
    {
        // Creates an object with filled out fields
        $model = factoryJsonapi($this->getModelClassName())->create();

        // Creates collection
        $collection = factoryJsonapi($this->getModelClassName('related'), 5)->create(['TST_ID' => $model->getKey()]);
        $collection = $collection->sortBy('REL_ID')->values();

        // Sets test params
        $options = [
            'page' => 1,
            'itemPerPage' => config('json-api-paginate.max_results'),
            'colCount' => $collection->count(),
            'resourceType' => $this->getResourceClassName()
        ];

        // Sends request and gets response
        $url = route("{$this->routeName}.related.show", ['id' => $model->getKey(), 'relationship' => 'relatedToMany']);
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
    }

    /**
     * GET /endpoint/<id>
     * Should return 200 with data
     *
     * @return void
     */
    public function testFetchRelatedAsEmptyResourceCollection()
    {
        // Creates an object with filled out fields
        $model = factoryJsonapi($this->getModelClassName())->create();

        // Sends request and gets response
        $url = route("{$this->routeName}.related.show", ['id' => $model->getKey(), 'relationship' => 'relatedToMany']);
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
    }

    /**
     * GET /endpoint/<id>
     * Should return 404
     *
     * @return void
     */
    public function testFetchInexistantRelated()
    {
        // Creates an object with filled out fields
        $model = factoryJsonapi($this->getModelClassName())->create();

        // Sends request and gets response
        $url = route("{$this->routeName}.related.show", ['id' => $model->getKey(), 'relationship' => 'inexistantRelated']);
        $response = $this->json('GET', $url);

        // Check response status code
        $response->assertStatus(404);
    }
}
