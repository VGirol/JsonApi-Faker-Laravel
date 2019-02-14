<?php
namespace VGirol\JsonApi\Tests\Unit\Resource;

use Illuminate\Http\Request;
use VGirol\JsonApi\Resources\JsonApiResourceType;
use VGirol\JsonApi\Tests\TestCase;

class JsonApiResourceTest extends TestCase
{
    protected $resourceClass = \VGirol\JsonApi\Resources\JsonApiResource::class;

    public function testExportAsResourceIdentifierObject()
    {
        // Creates an object with filled out fields
        $model = factoryJsonapi($this->getModelClassName())->make();

        // Creates a resource
        $resource = call_user_func_array([$this->getResourceClassName(), 'make'], [$model]);
        $resource->setExportType(JsonApiResourceType::RESOURCE_IDENTIFIER);

        // Creates a request
        $request = Request::create($this->endpoint, 'GET', []);

        // Export model as resource identifier object
        $data = $resource->toArray($request);

        // Executes all the tests
        $this->assertIsValidResourceIdentifierObject($data);
        $this->assertResourceIdentifierObjectEqualsModel($model, $data);
        $this->assertNotHasMember($data, 'meta');
    }

    public function testExportAsResourceObject()
    {
        // Creates an object with filled out fields
        $model = factoryJsonapi($this->getModelClassName())->make();

        // Creates a resource
        $resource = call_user_func_array([$this->getResourceClassName(), 'make'], [$model]);
        $resource->setExportType(JsonApiResourceType::RESOURCE_OBJECT);

        // Creates a request
        $request = Request::create($this->endpoint, 'GET', []);

        // Export model as resource object
        $data = $resource->toArray($request);

        // Executes all the tests
        $this->assertIsValidResourceObject($data);
        $this->assertResourceObjectEqualsModel($model, $data);
        $this->assertNotHasMember($data, 'meta');
    }

    public function testExportAsResourceObjectWithRelationships()
    {
        $count = 5;
        $relatedName = 'related';

        // Creates an object with filled out fields
        $model = factoryJsonapi($this->getModelClassName())->make();
        $related = factory(
            $this->getModelClassName($relatedName),
            $count
        )->make([
            'TST_ID' => $model->getKey(),
        ]);

        // Creates a resource
        $resource = call_user_func_array([$this->getResourceClassName(), 'make'], [$model]);
        $resource->setExportType(JsonApiResourceType::RESOURCE_OBJECT);
        $resource->addRelationship(function () use ($relatedName, $related) {
            $className = $this->getResourceCollectionClassName($relatedName);
            return [
                $relatedName => new $className($related)
            ];
        });

        // Creates a request
        $request = Request::create($this->endpoint, 'GET', []);

        // Export model as resource object
        $data = $resource->toArray($request);

        // Executes all the tests
        $this->assertIsValidResourceObject($data);
        $this->assertResourceObjectEqualsModel($model, $data);
        $this->assertNotHasMember($data, 'meta');
        $this->assertHasRelationships($data);
        $this->assertIsValidRelationshipsObject($data['relationships']);
        $this->assertCount(1, $data['relationships']);
        foreach ($data['relationships'] as $name => $relationship) {
            $this->assertHasData($relationship);
            $this->assertCount($count, $relationship['data']);
        }
    }
}
