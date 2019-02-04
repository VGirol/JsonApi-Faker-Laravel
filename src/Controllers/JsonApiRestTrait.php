<?php

namespace VGirol\JsonApi\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Database\Eloquent\Model;
use VGirol\JsonApi\Tools\ClassNameTools;
use Illuminate\Foundation\Http\FormRequest;
use VGirol\JsonApi\Resources\JsonApiResource;
use VGirol\JsonApi\Requests\JsonApiFormRequest;
use VGirol\JsonApi\Controllers\JsonApiHttpResponseTrait;
use VGirol\JsonApi\Resources\JsonApiResourceType;

trait JsonApiRestTrait
{
    use ClassNameTools;
    use JsonApiHttpResponseTrait;

    private $query = [
        'includes' => [],
        'fields' => [],
        'sort' => [],
        'pagination' => [],
        'filters' => []
    ];

    /**
     * Display a listing of the resource.
     *
     * @param   Illuminate\Http\Request     $request
     * @return  Illuminate\Http\JsonResponse
     */
    public function index(Request $request) : JsonResponse
    {
        // Create QueryBuilder object for sorting, filtering, including ...
        $queryBuilder = $this->getListQueryBuilder($request);

        // pagination
        $method_name = config('json-api-paginate.method_name');

        // Get total items before pagination
        $itemTotal = $queryBuilder->count();

        // Paginate collection
        $builder = call_user_func([$queryBuilder, $method_name]);

        // Resource class names
        $resourceName = $this->getResourceCollectionClassName();

        // Resource object
        $resource = call_user_func_array([$resourceName, 'make'], [$builder]);
        $resource->setExportType(JsonApiResourceType::RESOURCE_OBJECT);

        // Set collection to "data" member of json response
        $this->setData($resource);

        // Add pagination info (meta and links members) to json response
        $this->addPaginationToIndex($request, $itemTotal);

        // Return response
        return $this->ok();
    }

    private function getListQueryBuilder(Request $request)
    {
        // Filtering
        $filters = $request->query('filter', null);

        // Including relationship
        $include = $request->query('include');

        // Model class names
        $modelName = $this->getModelClassName();

        // Create QueryBuilder object for sorting, filtering, ...
        $queryBuilder = QueryBuilder::for($modelName);
        if (!is_null($filters)) {
            $queryBuilder->allowedFilters(array_keys($filters));
        }

        // Get relationships only for paginate collection
        if (!is_null($include)) {
            $queryBuilder->allowedIncludes(explode(',', $include));
        }

        return $queryBuilder;
    }

    private function addPaginationToIndex(Request $request, $itemTotal)
    {
        // Filtering
        $filters = $request->query('filter', null);

        // Sorting
        $sort = $request->query('sort', null);

        // pagination
        $number_parameter = config('json-api-paginate.number_parameter');
        $size_parameter = config('json-api-paginate.size_parameter');
        $pagination = $request->query('page', null);
        $page = $pagination[$number_parameter] ? : 1;
        $itemPerPage = $pagination[$size_parameter] ? : config('json-api-paginate.max_results');

        // Set "meta" member of json response
        $pageCount = intdiv($itemTotal, $itemPerPage);
        if ($itemTotal % $itemPerPage != 0) {
            $pageCount++;
        }

        $this->setMeta([
            'pagination' => [
                'total_items' => $itemTotal,
                'item_per_page' => $itemPerPage,
                'page_count' => $pageCount,
                'page' => $page
            ]
        ]);

        // Set top level "links" member of json response
        $type = $this->getObjectResourceType();
        $links = [
            'first' => route($type . '.index', ["page[{$number_parameter}]" => 1, "page[{$size_parameter}]" => $itemPerPage]),
            'last' => route($type . '.index', ["page[{$number_parameter}]" => $pageCount, "page[{$size_parameter}]" => $itemPerPage]),
            'prev' => null,
            'next' => null
        ];
        if ($itemTotal > $itemPerPage) {
            if ($page > 1) {
                $links['prev'] = route($type . '.index', ["page[{$number_parameter}]" => $page - 1, "page[{$size_parameter}]" => $itemPerPage]);
            }
            if ($page < $pageCount) {
                $links['next'] = route($type . '.index', ["page[{$number_parameter}]" => $page + 1, "page[{$size_parameter}]" => $itemPerPage]);
            }
        }
        foreach ($links as $name => $url) {
            if (!is_null($url)) {
                if (!is_null($sort)) {
                    $url .= "&sort={$sort}";
                }
                if (!is_null($filters)) {
                    foreach ($filters as $filter => $value) {
                        $url .= "&filter[{$filter}]={$value}";
                    }
                }
            }
            $this->addToLinks($name, $url);
        }
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
        $model = $this->getModelInstance($request, $id);

        // Creates resource
        $resource = $this->getResourceInstance($request, $model);
        $resource->setExportType(JsonApiResourceType::RESOURCE_OBJECT);

        // Fill response's content
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
        // Create model
        $columns = $this->createColumnsArray($request->input('data.attributes'), $this->getModelTable());
        $model = $this->createModel($request, $columns);

        // Creates resource
        $resource = $this->getResourceInstance($request, $model);
        $resource->setExportType(JsonApiResourceType::RESOURCE_OBJECT);

        // Fill response's content
        $this->setData($resource);

        return $this->created();
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
        $model = $this->getModelInstance($request, $id);
        if (!is_null($model)) {
            if (!$model->update($columns)) {
                throw new \Exception('Error updating model.');
            }
        }

        // Creates resource
        // $resourceName = $this->getResourceClassName();
        // $resource = call_user_func([$resourceName, 'make'], [$model]);
        $resource = $this->getResourceInstance($request, $model);
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
    public function destroy($id) : JsonResponse
    {
        $model = $this->destroyModel($id);

        return $this->noContent();
    }

    /**
     * Display a listing of the resource.
     *
     * @param   Illuminate\Http\Request     $request
     * @param   int  $id
     * @return  Illuminate\Http\JsonResponse
     */
    public function relationships(Request $request, $id) : JsonResponse
    {
        $a = $request->segments();
        $relationships = array_pop($a);

        $collection = $this->getModelInstance($request, $id)->$relationships;

        $resourceName = $this->getResourceCollectionClassName($relationships);
        $resource = call_user_func([$resourceName, 'make'], [$collection]);
        $this->setData($resouce);

        return $this->ok();
    }

    protected function createModel(Request $request, array $columns) : Model
    {
        return call_user_func_array(
            [$this->getModelClassName(), 'create'],
            [$columns]
        );
    }

    protected function getResourceInstance(Request $request, $model): ? JsonApiResource
    {
        return call_user_func_array(
            [$this->getResourceClassName(), 'make'],
            [$model]
        );
    }

    protected function getModelInstance(Request $request, int $id) : ? Model
    {
        return call_user_func_array(
            [$this->getModelClassName(), 'findOrFail'],
            [$id]
        );
    }

    protected function destroyModel($id) : ? Model
    {
        $model = $this->getModelInstance($request, $id);
        if (!is_null($model)) {
            $model->delete();
        }

        return $model;
    }

    private function createColumnsArray(array $request, string $table) : array
    {
        $a = [];
        $columns = DB::getSchemaBuilder()->getColumnListing($table);
        foreach ($columns as $key) {
            $aFields = [
                $key,
                strtolower($key),
                substr(strtolower($key), 4)
            ];
            foreach ($aFields as $field) {
                if (!isset($request[$field])) {
                    continue;
                }
                $a[$key] = $request[$field];
            }
        }

        return $a;
    }

    // protected function getCollectionInstance(Request $request): LengthAwarePaginator
    // {
    //     $page = $request->get('page');
    //     $itemsPerPage = $request->get('items');

    //     $paginator = call_user_func([$this->getModelClassName(), 'paginate'], $itemsPerPage);
    //     var_dump($paginator);

    //     if (is_null($page) || ($itemsPerPage == 0)) {
    //         $list = call_user_func([$this->getModelClassName(), 'all']);
    //     } else {
    //         $paginator = call_user_func([$this->getModelClassName(), 'paginate'], $itemsPerPage);
    //         $list = $paginator->getCollection();
    //     }

    //     return $list;
    // }
    private function parseQuery(Request $request)
    {
        $this->query = [
            'includes' => explode(',', $request->query('include', NULL)),
            'fields' => $request->query('fields', NULL),
            'sort' => $request->query('sort', NULL),
            'pagination' => $request->query('page', null),
            'filters' => $request->query('filter', null)
        ];
    }
}
