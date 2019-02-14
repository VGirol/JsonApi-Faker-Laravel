<?php
namespace VGirol\JsonApi\Tests\Unit\Resource;

use Illuminate\Http\Request;
use VGirol\JsonApi\Resources\JsonApiResourceType;
use VGirol\JsonApi\Tests\TestCase;

class JsonApiResourceCollectionTest extends TestCase
{

    public function testExportAsCollectionOfResourceIdentifierObject()
    {
        // Creates a collection of n objects with filled out fields
        $count = 3;
        $collection = factoryJsonapi($this->getModelClassName(), $count)->make();

        // Creates a resource collection
        $resource = call_user_func_array([$this->getResourceCollectionClassName(), 'make'], [$collection]);
        $resource->setExportType(JsonApiResourceType::RESOURCE_IDENTIFIER);

        // Creates a request
        $request = Request::create($this->endpoint, 'GET', []);

        // Export collection as resource identifier objects
        $data = $resource->toArray($request);

        // Executes all the tests
        $this->assertIsArrayOfObjects($data);

        $model = $collection->first();
        $obj = $data[0];
        $this->assertIsValidResourceIdentifierObject($obj);
        $this->assertNotHasMember($obj, 'meta');
        $this->assertResourceIdentifierObjectEqualsModel($model, $obj);
    }

    public function testExportAsCollectionOfResourceObject()
    {
        // Creates a collection of n objects with filled out fields
        $count = 3;
        $collection = factoryJsonapi($this->getModelClassName(), $count)->make();

        // Creates a resource collection
        $resource = call_user_func_array([$this->getResourceCollectionClassName(), 'make'], [$collection]);
        $resource->setExportType(JsonApiResourceType::RESOURCE_OBJECT);

        // Creates a request
        $request = Request::create($this->endpoint, 'GET', []);

        // Export collection as resource identifier objects
        $data = $resource->toArray($request);

        // Executes all the tests
        $this->assertIsArrayOfObjects($data);

        $model = $collection->first();
        $obj = $data[0];
        $this->assertIsValidResourceObject($obj);
        $this->assertNotHasMember($obj, 'meta');
        $this->assertResourceObjectEqualsModel($model, $obj);
    }
}
