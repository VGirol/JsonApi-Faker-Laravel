<?php

namespace VGirol\JsonApi\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Database\QueryException;
use VGirol\JsonApi\Tools\ClassNameTools;
use VGirol\JsonApi\Requests\JsonApiFormRequest;
use VGirol\JsonApi\Resources\JsonApiResourceType;
use Illuminate\Database\Eloquent\Relations\Relation;
use VGirol\JsonApi\Controllers\JsonApiHttpResponseTrait;
use VGirol\JsonApi\Exceptions\JsonApiDuplicateEntryException;

trait JsonApiRestTrait
{
    use ClassNameTools;
    use JsonApiControllerTrait;
    use JsonApiHttpResponseTrait;

    /**
     * Display a listing of the resource.
     *
     * @param   Illuminate\Http\Request     $request
     * @return  Illuminate\Http\JsonResponse
     */
    public function index(Request $request) : JsonResponse
    {
        // Clean sort parameter
        $sortName = config('query-builder.parameters.sort');
        $sort = $request->query($sortName, null);
        if (!is_null($sort)) {
            $request->query->set($sortName, str_replace('+', null, $sort));
        }

        // Model class name
        $modelName = $this->getModelClassName();

        // Gets the collection
        list($collection, $itemTotal) = $this->getCollection($request, $modelName);

        // Resource class name
        $resourceName = $this->getResourceCollectionClassName();

        // Resource object
        $resource = $this->createResourceCollectionInstance($collection, $resourceName, JsonApiResourceType::RESOURCE_OBJECT);

        // Set collection to "data" member of json response
        $this->setData($resource);

        // Add pagination info (meta and links members) to json response
        $this->addPaginationToIndex($request, $itemTotal);

        // Return response
        return $this->ok();
    }

    /**
     * Display the specified resource.
     *
     * @param   Illuminate\Http\Request     $request
     * @param   int                         $id
     * @return  Illuminate\Http\JsonResponse
     */
    public function show(Request $request, $id) : JsonResponse
    {
        // Loads model
        $model = $this->getModelInstance($id);

        // Gets resource class name
        $resourceName = $this->getResourceClassName();

        // Creates resource
        $resource = $this->createSingleResourceInstance($model, $resourceName, JsonApiResourceType::RESOURCE_OBJECT);

        // Fills response's content
        $this->setData($resource);

        return $this->ok();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param   App\Http\Requests\JsonApiFormRequest    $request
     * @return  Illuminate\Http\JsonResponse
     */
    public function storeObject(JsonApiFormRequest $request) : JsonResponse
    {
        // Retrieve client-generated ID
        $id = $request->input('data.id', null);

        $return204 = !is_null($id) && config('jsonapi.return204');

        // Create model
        $columns = $this->createColumnsArray($request->input('data.attributes'), $this->getModelTable());
        if (!is_null($id)) {
            // Model key MUST be mass assignable
            $columns[$this->getModelKeyName()] = $id;
        }
        try {
            $model = $this->createModel($request, $columns);
        } catch (QueryException $e) {
            if ($this->isDuplicateEntryException($e)) {
                throw new JsonApiDuplicateEntryException($e->getSql(), $e->getBindings(), $e);
            }
            throw $e;
        }

        // Creates resource
        $resourceName = $this->getResourceClassName($model);
        $resource = call_user_func([$resourceName, 'make'], $model);
        $resource->setExportType(JsonApiResourceType::RESOURCE_OBJECT);

        // Fill response's content
        if ($return204) {
            $response = $this->noContent();
        } else {
            $this->setData($resource);

            $response = $this->created();
        }

        return $response->header('Location', $resource->getLocation());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param   App\Http\Requests\JsonApiFormRequest    $request
     * @param   int  $id
     * @return  \Illuminate\Http\JsonResponse
     */
    public function updateObject(JsonApiFormRequest $request, $id) : JsonResponse
    {
        // Update model
        $columns = $this->createColumnsArray($request->input('data.attributes'), $this->getModelTable());
        $model = $this->findModelInstance($id);
        if (!is_null($model)) {
            if (!$model->update($columns)) {
                throw new \Exception('Error updating model.');
            }
        }

        // Creates resource
        $resourceName = $this->getResourceClassName($model);
        $resource = call_user_func([$resourceName, 'make'], $model);
        $resource->setExportType(JsonApiResourceType::RESOURCE_OBJECT);

        // Fill response's content
        $this->setData($resource);

        return $this->ok();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\: JsonResponse
     */
    public function destroy(Request $request, $id) : JsonResponse
    {
        $model = $this->destroyModel($request, $id);

        return $this->noContent();
    }

    /**
     * Display a listing of the resource.
     *
     * @param   Illuminate\Http\Request     $request
     * @param   int  $id
     * @return  Illuminate\Http\JsonResponse
     */
    public function relationships(Request $request, $id, $relationship) : JsonResponse
    {
        // Loads model
        $model = $this->getModelInstance($id);
        $query = $model->$relationship();

        if ($this->isToOneRelationship($query)) {
            // Gets resource class name
            $resourceName = $this->getResourceClassName($relationship);

            // Creates resource
            $resource = $this->createSingleResourceInstance($query->getResults(), $resourceName, JsonApiResourceType::RESOURCE_IDENTIFIER);
        } else {
            // Gets the collection
            list($collection, $itemTotal) = $this->getCollection($request, $query->getQuery());

            // Gets resource class name
            $resourceName = $this->getResourceCollectionClassName($relationship);

            // Creates resource
            $resource = $this->createResourceCollectionInstance($collection, $resourceName, JsonApiResourceType::RESOURCE_IDENTIFIER);

            // Add pagination info (meta and links members) to json response
            $this->addPaginationToIndex($request, $itemTotal);
        }

        // Set collection to "data" member of json response
        $this->setData($resource);

        // Fills links
        $self = route($this->getObjectResourceType() . '.relationships', ['id' => $id, 'relationship' => $relationship]);
        $this->addToLinks('self', $self);

        return $this->ok();
    }

    /**
     * Display a listing of the resource.
     *
     * @param   Illuminate\Http\Request     $request
     * @param   int  $id
     * @return  Illuminate\Http\JsonResponse
     */
    public function showRelated(Request $request, $id, $relationship) : JsonResponse
    {
        // Loads model
        $model = $this->getModelInstance($id);
        if (!method_exists($model, $relationship)) {
            return $this->notFound('Inexistant relationship');
        }
        $query = $model->$relationship();

        // Creates resource
        if ($this->isToOneRelationship($query)) {
            // Gets resource class name
            $resourceName = $this->getResourceClassName($relationship);

            // Creates resource
            $resource = $this->createSingleResourceInstance($query->getResults(), $resourceName, JsonApiResourceType::RESOURCE_OBJECT);
        } else {
            // Gets the collection
            list($collection, $itemTotal) = $this->getCollection($request, $query->getQuery());

            // Gets resource class name
            $resourceName = $this->getResourceCollectionClassName($relationship);

            // Creates resource
            $resource = $this->createResourceCollectionInstance($collection, $resourceName, JsonApiResourceType::RESOURCE_OBJECT);

            // Add pagination info (meta and links members) to json response
            $this->addPaginationToIndex($request, $itemTotal);
        }

        // Fills response's content
        $this->setData($resource);

        return $this->ok();
    }
}
