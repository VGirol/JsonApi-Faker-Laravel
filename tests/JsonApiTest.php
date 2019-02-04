<?php
namespace VGirol\JsonApi\Tests;

use VGirol\JsonApi\Tools\Assert\JsonApiAssertBase;
use VGirol\JsonApi\Tools\Assert\JsonApiAssertObject;
use VGirol\JsonApi\Tools\Assert\JsonApiAssertResource;
use VGirol\JsonApi\Tools\Assert\JsonApiAssertStructure;

trait JsonApiTest
{
    use JsonApiAssertBase, JsonApiAssertObject, JsonApiAssertStructure;
    use JsonApiAssertResource;

    /**
     * The model to use when creating dummy data
     *
     * @var class
     */
    protected $model;

    /**
     * The endpoint to query in the API
     * e.g = /api/<endpoint>
     *
     * @var string
     */
    protected $endpoint;

    /**
     * The resource type
     *
     * @var string
     */
    protected $resourceType;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        $this->customConstruct($name, $data, $dataName);
    }

    abstract protected function setModel(): string;
    abstract protected function setEndpoint(): string;

    protected function customConstruct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->model = $this->setModel();
        $this->endpoint = $this->setEndpoint();
        $this->setObjectResourceType();
    }

    protected function setObjectResourceType()
    {
        $obj = new $this->model();
        $this->resourceType = $obj->getResourceType();
    }
}
