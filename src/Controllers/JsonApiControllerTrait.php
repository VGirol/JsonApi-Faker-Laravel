<?php

namespace VGirol\JsonApi\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\Relations\Relation;

trait JsonApiControllerTrait
{
    private function getModelInstance($id)
    {
        return call_user_func([$this->getModelClassName(), 'findOrFail'], $id);
    }

    private function createResourceCollectionInstance($collection, $resourceName, $resourceType)
    {
        return $this->createResourceInstance($collection, $resourceName, $resourceType);
    }

    private function createSingleResourceInstance($model, $resourceName, $resourceType)
    {
        if (is_null($model)) {
            return null;
        }

        return $this->createResourceInstance($model, $resourceName, $resourceType);
    }

    private function createResourceInstance($obj, $resourceName, $resourceType)
    {
        $resource = call_user_func([$resourceName, 'make'], $obj);
        $resource->setExportType($resourceType);

        return $resource;
    }

    private function getCollection(Request $request, $model)
    {
        // Create QueryBuilder object for sorting, filtering, including ...
        $queryBuilder = $this->getQueryBuilder($request, $model);

        // Get total items before pagination
        $itemTotal = $queryBuilder->count();

        if (config('jsonapi.withPagination', false)) {
            // Pagination method
            $method_name = config('json-api-paginate.method_name');

            // Paginate collection
            $builder = call_user_func([$queryBuilder, $method_name]);
        }

        return [$builder->getCollection(), $itemTotal];
    }

    private function getQueryBuilder(Request $request, $baseQuery)
    {
        // Create QueryBuilder object for sorting, filtering, ...
        $queryBuilder = QueryBuilder::for($baseQuery, $request);

        // Filtering
        $filters = $request->query('filter', null);
        if (!is_null($filters)) {
            $queryBuilder->allowedFilters(array_keys($filters));
        }

        // Including relationship
        $include = $request->query('include');

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
        if (!is_null($sort) && !in_array(mb_substr($sort, 0, 1), [ '+', '-' ])) {
            $sort = '+'.$sort;
        }

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

    private function isToOneRelationship($query) : bool
    {
        if (!is_subclass_of($query, Relation::class)) {
            throw new \Exception('Must be subclass of ' . Relation::class . '.');
        }

        $toOne = [
            \Illuminate\Database\Eloquent\Relations\BelongsTo::class,
            \Illuminate\Database\Eloquent\Relations\HasOne::class,
            \Illuminate\Database\Eloquent\Relations\MorphOne::class,
            \Illuminate\Database\Eloquent\Relations\MorphTo::class
        ];

        return in_array(get_class($query), $toOne);
    }


    private function createModel(Request $request, array $columns)
    {
        return call_user_func_array(
            [$this->getModelClassName(), 'create'],
            [$columns]
        );
    }

    private function destroyModel(Request $request, $id)
    {
        $model = $this->getModelInstance($id);
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

    private function isDuplicateEntryException(QueryException $e)
    {
        $sqlState = $e->errorInfo[0];
        $errorCode = $e->errorInfo[1];
        if ($sqlState === "23000" && $errorCode === 1062) {
            return true;
        }
        return false;
    }
}
