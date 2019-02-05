<?php
namespace VGirol\JsonApi\Tests\Unit\Resource;

use Illuminate\Http\Request;
use VGirol\JsonApi\Tests\TestCase;
use VGirol\JsonApi\Tools\Assert\JsonApiTest;
use VGirol\JsonApi\Tests\JsonApiTestCommon;
use VGirol\JsonApi\Resources\JsonApiResourceType;

class JsonApiResourceTest extends TestCase
{
    public function testExportAsResourceIdentifierObject()
    {
        // Creates an object with filled out fields
        $model = factoryJsonapi($this->model)->make();

        // Creates a resource
        $resource = call_user_func_array([$this->resourceClass, 'make'], [$model]);
        $resource->setExportType(JsonApiResourceType::RESOURCE_IDENTIFIER);

        // Creates a request
        $request = Request::create($this->endpoint, 'GET', []);

        // Export model as resource identifier object
        $data = $resource->toArray($request);

        // Executes all the tests
        $this->assertIsResourceIdentifierObject($data);
        $this->assertValidResourceIdentifierObject($data, $model);
        $this->assertNotHasMember($data, 'meta');
    }

    public function testExportAsResourceObject()
    {
        // Creates an object with filled out fields
        $model = factoryJsonapi($this->model)->make();

        // Creates a resource
        $resource = call_user_func_array([$this->resourceClass, 'make'], [$model]);
        $resource->setExportType(JsonApiResourceType::RESOURCE_OBJECT);

        // Creates a request
        $request = Request::create($this->endpoint, 'GET', []);

        // Export model as resource object
        $data = $resource->toArray($request);

        // Executes all the tests
        $this->assertIsResourceObject($data);
        $this->assertValidResourceObject($data, $model);
        $this->assertNotHasMember($data, 'meta');
    }

    public function testExportAsResourceObjectWithRelationships()
    {
        $count = 5;
        $relatedName = 'related';

        // Creates an object with filled out fields
        $model = factoryJsonapi($this->model)->make();
        $related = factory(
            \VGirol\JsonApi\Tests\Tools\Models\RelatedModelForTest::class,
            $count
        )->make([
            'TST_ID' => $model->getKey(),
        ]);
        // $model->relatedModelForTest()->createMany($related);

        // Creates a resource
        $resource = call_user_func_array([$this->resourceClass, 'make'], [$model]);
        $resource->setExportType(JsonApiResourceType::RESOURCE_OBJECT);
        $resource->addRelationship(function () use ($relatedName, $related) {
            return [
                $relatedName => new \VGirol\JsonApi\Resources\JsonApiResourceCollection($related)
            ];
        });

        // Creates a request
        $request = Request::create($this->endpoint, 'GET', []);

        // Export model as resource object
        $data = $resource->toArray($request);

        // Executes all the tests
        $this->assertIsResourceObject($data);
        $this->assertValidResourceObject($data, $model);
        $this->assertNotHasMember($data, 'meta');
        $this->assertHasRelationships($data);
        $this->checkRelationshipsObject($data['relationships']);
        $this->assertCount(1, $data['relationships']);
        foreach ($data['relationships'] as $name => $relationship) {
            $this->assertHasData($relationship);
            $this->assertCount($count, $relationship['data']);
        }
    }
}
