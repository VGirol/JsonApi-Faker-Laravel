# JsonApi-Assert-Laravel

This package adds a lot of methods to the [`Illuminate\Foundation\Testing\TestResponse`](https://laravel.com/api/5.8/Illuminate/Foundation/Testing/TestResponse.html) class for testing APIs that implements the [JSON:API specification](https://jsonapi.org/).

## Table of content

## Technologies

- PHP 7.2+
- PHPUnit 8.0+
- Laravel 5.8+
- JsonApi-Assert

## Installation

To install through composer, simply put the following in your `composer.json` file:

```json
{
    "require-dev": {
        "vgirol/jsonapi-assert-laravel": "dev-master"
    }
}
```

And then run `composer install` from the terminal.

### Quick Installation

Above installation can also be simplified by using the following command:

```sh
composer require vgirol/jsonapi-assert-laravel
```

### Registration

The package will automatically register itself.  
If you're not using Package Discovery, add the Service Provider to your config/app.php file:

```php
VGirol\JsonApiAssert\Laravel\JsonApiAssertServiceProvider::class
```

## Usage

This package provides the class `VGirol\JsonApiAssert\Laravel\Assert`. That class extends the `VGirol\JsonApiAssert\Assert` class with new assertions methods.


## Assertions (`VGirol\JsonApiAssert\Laravel\Assert`)

### assertErrorsContains

Asserts that an errors array contains a given subset of expected errors.

Definition:
`assertErrorsContains($expectedErrors, $errors, $strict)`

Parameters :
- `$expectedErrors` (array)
- `$errors` (array)
- `$strict` (boolean) : if true, unsafe characters are not allowed when checking members name.

It will do the following checks :
- asserts that the errors array is valid.
- asserts that the errors array length is greater or equal than the expected errors array length.
- asserts that each expected error is present in the errors array.

### assertFetchedRelationshipsResponse

Asserts that the response has 200 status code and content with primary data represented as resource identifier objects and corresponding to the provided collection or model and resource type.

Definition:
`assertFetchedRelationshipsResponse(TestResponse $response, $expected, $resourceType, $strict)`

Parameters :
- `$response` (Illuminate\Foundation\Testing\TestResponse)
- `$expected` (Illuminate\Database\Eloquent\Collection|Model|null)
- `$resourceType` (string)
- `$strict` (boolean) : if true, unsafe characters are not allowed when checking members name.

It will do the following checks :
- asserts that the response has `200 OK` status code
- asserts that the response has header `Content-Type: application/vnd.api+json` without any media type parameters
- asserts that the response has content with primary data represented as resource identifier objects and corresponding to the provided collection or model and resource type ([assertResourceLinkageEquals](#assertResourceLinkageEquals)).

### assertFetchedResourceCollectionResponse

Asserts that the response has 200 status code and content with primary data corresponding to the provided collection and resource type.

Definition :  
`assertFetchedResourceCollectionResponse(TestResponse $response, $expectedCollection, $expectedResourceType, bool $strict)`

Parameters :
- `$response` (Illuminate\Foundation\Testing\TestResponse)
- `$expectedCollection` (Illuminate\Database\Eloquent\Collection)
- `$expectedResourceType` (string)
- `$strict` (boolean) : if true, unsafe characters are not allowed when checking members name.

It will do the following checks :
- asserts that the response has `200 OK` status code
- asserts that the response has header `Content-Type: application/vnd.api+json` without any media type parameters
- asserts that the response has content with primary data corresponding to the provided collection and resource type ([assertResourceCollectionEquals](#assertResourceCollectionEquals)).

### assertFetchedSingleResourceResponse

Asserts that the response has 200 status code and content with primary data corresponding to the provided model and resource type.

Definition :  
`assertFetchedSingleResourceResponse($response, $expectedModel, $resourceType, $strict)`

Parameters :
- `$response` (Illuminate\Foundation\Testing\TestResponse)
- `$expectedModel` (Illuminate\Database\Eloquent\Model)
- `$resourceType` (string)
- `$strict` (boolean) : if true, unsafe characters are not allowed when checking members name.

It will do the following checks :
- asserts that the response has `200 OK` status code
- asserts that the response has header `Content-Type: application/vnd.api+json` without any media type parameters
- asserts that the response has content with primary data corresponding to the provided model and resource type ([assertResourceObjectEquals](#assertResourceObjectEquals)).

### assertIsCreatedResponse

Asserts that a response object is a valid '201 Created' response following a creation request.

Definition :  
`assertIsCreatedResponse($response, $expectedModel, $resourceType, $strict)`

Parameters :
- `$response` (Illuminate\Foundation\Testing\TestResponse)
- `$expectedModel` (Illuminate\Database\Eloquent\Model)
- `$resourceType` (string)
- `$strict` (boolean) : if true, unsafe characters are not allowed when checking members name.

It will do the following checks :
- asserts that the response has `201 Created` status code.
- asserts that the response has header `Content-Type: application/vnd.api+json` without any media type parameters.
- asserts that the response has content with primary data corresponding to the provided model and resource type ([assertResourceObjectEquals](#assertResourceObjectEquals)).
- if the resource object returned by the response contains a `self` key in its `links` member and a `Location` header is provided, this methods asserts that the value of the  `self` member matches the value of the `Location` header.

### assertIsDeletedResponse

Asserts that a response object is a valid '200 OK' response following a deletion request.

Definition :  
`assertIsDeletedResponse($response, $expectedMeta = null, $strict = false)`

Parameters :
- `$response` (Illuminate\Foundation\Testing\TestResponse)
- `$expectedMeta` (array) : if not null, it is the expected "meta" object.
- `$strict` (boolean) : if true, unsafe characters are not allowed when checking members name.

It will do the following checks :
- asserts that the response has `200 OK` status code.
- asserts that the response has header `Content-Type: application/vnd.api+json` without any media type parameters.
- asserts that the response has content with only `meta` or `jsonapi` members.
- if expected, asserts that the `meta` member is equal to the provided meta object.

### assertIsErrorResponse

Asserts that an error response (status code 4xx) is valid.

Definition :  
`assertIsErrorResponse($response, $status, $errors, $strict)`

Parameters :
- `$response` (Illuminate\Foundation\Testing\TestResponse)
- `$status` (integer)
- `$errors` (array) : an array of the expected error objects.
- `$strict` (boolean) : if true, unsafe characters are not allowed when checking members name.

It will do the following checks :
- asserts that the response has the given status code.
- asserts that the response has header `Content-Type: application/vnd.api+json` without any media type parameters.
- asserts that the response has content with `errors` member that contains a subset corresponding to the provided error objects ([assertErrorsContains](#assertErrorsContains)).

### assertIsNoContentResponse

Asserts that a response is a valid '204 No Content' response.

Definition :  
`assertIsNoContentResponse($response)`

Parameters :
- `$response` (Illuminate\Foundation\Testing\TestResponse)

It will do the following checks :
- asserts that the response has `204 No Content` status code.
- asserts that the response has header `Content-Type: application/vnd.api+json` without any media type parameters.
- asserts that the response has no content.

### assertIsUpdatedResponse

Asserts that a response object is a valid '200 OK' response following an update request.

Definition :  
`assertIsUpdatedResponse($response, $expectedModel, $resourceType, $strict)`

Parameters :
- `$response` (Illuminate\Foundation\Testing\TestResponse)
- `$expectedModel` (Illuminate\Database\Eloquent\Model)  
- `$resourceType` (string)  
- `$strict` (boolean) : if true, unsafe characters are not allowed when checking members name.

It will do the following checks :
- asserts that the response has `200 OK` status code.
- asserts that the response has header `Content-Type: application/vnd.api+json` without any media type parameters.
- asserts that the response has content with : 
  - either primary data corresponding to the model and resource type provided
  - or only `meta` member otherwise.

### assertResourceCollectionEquals

Asserts that an array of resource objects correspond to a given collection.

Definition :  
`assertResourceCollectionEquals($expectedCollection, $expectedResourceType, $collection)`

Parameters
- `$expectedCollection` (Illuminate\Database\Eloquent\Collection)
- `$expectedResourceType` (string)
- `$collection` (array)

It will do the following checks :
- asserts that the collection to check is an array of objects.
- asserts that the two collections have the same count of items.
- asserts that each resource object of the collection correspond to the model at the same index in the given expected collection ([assertResourceObjectEquals](#assertResourceObjectEquals)).


### assertResourceIdentifierCollectionEquals

Asserts that an array of resource identifer objects correspond to a given collection.

Definition :
`assertResourceIdentifierCollectionEquals($refCollection, $resourceType, $collection)`

Parameters :
- `$refCollection` (Illuminate\Database\Eloquent\Collection)
- `$resourceType` (string)
- `$collection` (array)

It will do the following checks :
- asserts that the given collection is an array of objects.
- asserts that the two collections have the same count of items.
- asserts that each resource identifier object of the collection correspond to the model at the same index in the given expected collection ([assertResourceIdentifierEquals](#assertResourceIdentifierEquals)).

### assertResourceIdentifierEquals

Asserts that a resource identifier object has "id" and "type" member equal to the given parameters.

Definition :
`assertResourceIdentifierEquals($expectedId, $expectedResourceType, $resource)`

Parameters :
- `$expectedId` (integer|string)
- `$expectedResourceType` (string)
- `$resource` (array)

It will do the following checks :
- asserts that "id" and "type" members of the resource identifier corresponds to the given parameters.

### assertResourceLinkageEquals

Asserts that a resource linkage object correspond to a given reference object which can be either the null value, a single resource identifier object, an empty collection or a collection of resource identifier ojects.

Definition :
`assertResourceLinkageEquals($reference, $resourceType, $resLinkage, $strict)`

Parameters :
- `$reference` (Illuminate\Database\Eloquent\Collection|Illuminate\Database\Eloquent\Model|null)
- `$resourceType` (string|null)
- `$resLinkage` (array|null)
- `$strict` (boolean, default : false) : if true, unsafe characters are not allowed when checking members name.

It will do the following checks :
- if the reference is `null`, asserts that the resource linkage is `null`.
- if the reference is an instance of the `Model` class, asserts that the resource linkage is not an array of objects and corresponds to the given reference object ([assertResourceIdentifierEquals](#assertResourceIdentifierEquals)).
- if the reference is an empty collection, asserts that the resource linkage is an empty array.
- if the reference is a collection, asserts that the resource linkage corresponds to the given collection ([assertResourceIdentifierCollectionEquals](#assertResourceIdentifierCollectionEquals))

### assertResourceObjectEquals

Asserts that a resource object correspond to a given model.

Definition :  
`assertResourceObjectEquals($expectedModel, $expectedResourceType, $resource)`

Parameters :
- `$expectedModel` (Illuminate\Database\Eloquent\Model)
- `$expectedResourceType` (string)
- `$resource` (array)

It will do the following checks :
- asserts that "id" and "type" members of the resource corresponds to the given model ([assertResourceIdentifierEquals](#assertResourceIdentifierEquals)).
- asserts that the resource has "attributes" member.
- asserts that the attributes object of the resource corresponds to the attributes of the given model.


## Macros (`Illuminate\Foundation\Testing\TestResponse`)

### assertJsonApiCreated

Definition :  
`assertJsonApiCreated'($expectedModel, $resourceType, $strict = false)`

Parameters :
- `$expectedModel` (Illuminate\Database\Eloquent\Model)
- `$resourceType` (string)
- `$strict` (boolean, default : false) : if true, unsafe characters are not allowed when checking members name.

See [`VGirol\JsonApiAssert\Laravel\Assert::assertIsCreatedResponse`](#assertIsCreatedResponse).

```php
use App\MyModel;

$id = 1;
$resourceType = 'my-model';

// Creates an object with filled out fields
$model = factory(MyModel::class)->make();
$model->setIdAttribute($id);

// Creates content of the request
$content = [
    'data' => [
        'type' => $resourceType,
        'id' => strval($id),
        'attributes' => $model->toArray()
    ]
];

// Sends request and gets response
$response = $this->json('POST', route('mymodel.store'), $content);

// Check response
$response->assertJsonApiCreated($model, 'my-model');
```

### assertJsonApiDeleted

Definition :  
`assertJsonApiDeleted($expectedMeta = null, $strict = false)`

Parameters :
- `$expectedMeta` (array, default null)
- `$strict` (boolean, default null) : if true, unsafe characters are not allowed when checking members name.

See [`VGirol\JsonApiAssert\Laravel\Assert::assertIsDeletedResponse`](#assertIsDeletedResponse).

```php
use App\MyModel;

// Creates an object with filled out fields
$model = factory(MyModel::class)->create();

$response = $this->json('DELETE', route('mymodel.destroy', ['id' => $model->getKey()]));

$expectedMeta = [
    'message' => 'Successfully deleted'
];

$response->assertJsonApiDeleted($expectedMeta);
```

### assertJsonApiErrorResponse

Definition :  
`assertJsonApiErrorResponse($status, $errors, $strict = false)`

Parameters :
- `$status` (integer)
- `$errors` (array) : the expected error objects.
- `$strict` (boolean, default false) : if true, unsafe characters are not allowed when checking members name.

See [`VGirol\JsonApiAssert\Laravel\Assert::assertIsErrorResponse`](#assertIsErrorResponse).

```php
// Sends request and gets response
$headers = ['Content-Type' => 'application/vnd.api+json; param=value'];
$response = $this->json('GET', route('mymodel.index'), [], $headers);

// Check response error
$response->assertJsonApiErrorResponse(
    415,
    [
        [
            'status' => '415',
            'title' => 'Unsupported Media Type',
            'details' => 'A request MUST specify the header "Content-Type: application/vnd.api+json" without any media type parameters.'
        ]
    ]
);
```

### assertJsonApiFetchedRelationships

Definition :  
`assertJsonApiFetchedRelationships($expected, $resourceType = null, $strict = false)`

Parameters :
- `$expected` (Illuminate\Database\Eloquent\Collection|Illuminate\Database\Eloquent\Model|null)
- `$resourceType` (string, default = null)
- `$strict` (boolean, default false) : if true, unsafe characters are not allowed when checking members name.

See [`VGirol\JsonApiAssert\Laravel\Assert::assertFetchedRelationshipsResponse`](#assertFetchedRelationshipsResponse).

```php
use App\MyModel;
use App\MyRelated;

// Creates an object with filled out fields
$model = factory(MyModel::class)->create();
$related = factory(MyRelated::class)->create(['model_id' => $model->getKey()]);

// Sends request and gets response
$response = $this->json('GET', route('mymodel.relationships', ['id' => $model->getKey(), 'relationship' => 'myrelated']));

$response->assertJsonApiFetchedRelationships($related, 'my-related');
```

### assertJsonApiFetchedResourceCollection

Definition :  
`assertJsonApiFetchedResourceCollection($expectedCollection, $expectedResourceType, $strict = false)`

Parameters :
- `$expectedCollection` (Illuminate\Database\Eloquent\Collection)
- `$expectedResourceType` (string)
- `$strict` (boolean, default false) : if true, unsafe characters are not allowed when checking members name.

See [`VGirol\JsonApiAssert\Laravel\Assert::assertFetchedResourceCollectionResponse`](#assertFetchedResourceCollectionResponse).

```php
use App\MyModel;

// Creates an object with filled out fields
$collection = factory(MyModel::class, 5)->create();

// Sends request and gets response
$response = $this->json('GET', route('mymodel.show'));

$response->assertJsonApiFetchedResourceCollection($collection, 'my-model');
```

### assertJsonApiFetchedSingleResource

Definition :  
`assertJsonApiFetchedSingleResource($expectedModel, $resourceType, $strict = false)`

Parameters :
- `$expectedModel` (Illuminate\Database\Eloquent\Model)
- `$resourceType` (string)
- `$strict` (boolean, default false) : if true, unsafe characters are not allowed when checking members name.

See [`VGirol\JsonApiAssert\Laravel\Assert::assertFetchedSingleResourceResponse`](#assertFetchedSingleResourceResponse).

```php
use App\MyModel;

// Creates an object with filled out fields
$model = factory(MyModel::class)->create();

// Sends request and gets response
$response = $this->json('GET', route('mymodel.show', ['id' => $model->getKey()]));

$response->assertJsonApiFetchedSingleResource($model, 'my-model');
```

### assertJsonApiNoContent

Definition :  
`assertJsonApiNoContent()`

See [`VGirol\JsonApiAssert\Laravel\Assert::assertIsNoContentResponse`](#assertIsNoContentResponse).

```php
use App\MyModel;

// Creates an object with filled out fields
$model = factory(MyModel::class)->create();

$response = $this->json('DELETE', route('mymodel.destroy', ['id' => $model->getKey()]));

$response->assertJsonApiNoContent();
```

### `assertJsonApiResponse400`

Definition :  
`assertJsonApiResponse400($errors, $strict = false)`

Parameters :
- `$errors` (array) : the expected error objects.
- `$strict` (boolean, default false) : if true, unsafe characters are not allowed when checking members name.

See [`assertJsonApiErrorResponse`](#assertJsonApiErrorResponse).

### `assertJsonApiResponse403`

Definition :  
`assertJsonApiResponse403($errors, $strict = false)`

Parameters :
- `$errors` (array) : the expected error objects.
- `$strict` (boolean, default false) : if true, unsafe characters are not allowed when checking members name.

See [`assertJsonApiErrorResponse`](#assertJsonApiErrorResponse).

### `assertJsonApiResponse404`

Definition :  
`assertJsonApiResponse404($errors, $strict = false)`

Parameters :
- `$errors` (array) : the expected error objects.
- `$strict` (boolean, default false) : if true, unsafe characters are not allowed when checking members name.

See [`assertJsonApiErrorResponse`](#assertJsonApiErrorResponse).

### `assertJsonApiResponse406`

Definition :  
`assertJsonApiResponse406($errors, $strict = false)`

Parameters :
- `$errors` (array) : the expected error objects.
- `$strict` (boolean, default false) : if true, unsafe characters are not allowed when checking members name.

See [`assertJsonApiErrorResponse`](#assertJsonApiErrorResponse).

### `assertJsonApiResponse409`

Definition :  
`assertJsonApiResponse409($errors, $strict = false)`

Parameters :
- `$errors` (array) : the expected error objects.
- `$strict` (boolean, default false) : if true, unsafe characters are not allowed when checking members name.

See [`assertJsonApiErrorResponse`](#assertJsonApiErrorResponse).

### `assertJsonApiResponse415`

Definition :  
`assertJsonApiResponse415($errors, $strict = false)`

Parameters :
- `$errors` (array) : the expected error objects.
- `$strict` (boolean, default false) : if true, unsafe characters are not allowed when checking members name.

See [`assertJsonApiErrorResponse`](#assertJsonApiErrorResponse).

### assertJsonApiUpdated

Definition :  
`assertJsonApiUpdated($expectedModel, $resourceType, $strict = false)`

See [`VGirol\JsonApiAssert\Laravel\Assert::assertIsUpdatedResponse`](#assertIsUpdatedResponse).

Parameters :
- `$expectedModel` (Illuminate\Database\Eloquent\Model)  
- `$resourceType` (string)  
- `$strict` (boolean, default : false) : if true, unsafe characters are not allowed when checking members name.

```php
use App\MyModel;

$resourceType = 'my-model';

// Creates an object with filled out fields
$model = factory(MyModel::class)->create();

// Update model
$model->setAttribute('attr', 'new value');

// Creates content of the request
$content = [
    'data' => [
        'type' => $resourceType,
        'id' => strval($model->getKey()),
        'attributes' => [
            'attr' => $model->attr
        ]
    ]
];

// Sends request and gets response
$response = $this->json('PATCH', route('mymodel.update', ['id' => $model->getKey()]), $content);

// Check response
$response->assertJsonApiUpdated($model, $resourceType);
```






















### `assertJsonApiFetchedToOneRelationships`

Asserts that the response has :
- `200 OK` status code
- header `Content-Type: application/vnd.api+json` without any media type parameters
- content with primary data represented as resource linkage and corresponding to the provided model and resource type.

```php
use Illuminate\Database\Eloquent\Model;

$model = new Model();
$response->assertJsonApiFetchedToOneRelationships($model, 'resourceType');
```

### `assertJsonApiFetchedToManyRelationships`

Asserts that the response has :
- `200 OK` status code
- header `Content-Type: application/vnd.api+json` without any media type parameters
- content with primary data represented as resource linkage and corresponding to the provided collection.

```php
use Illuminate\Database\Eloquent\Model;

$collection = new Collection([...]);
$response->assertJsonApiFetchedToManyRelationships($collection, 'resourceType');
```

### `assertJsonApiNoPaginationLinks`

Asserts that the json object specified by the path has no pagination links.

```php
$response->assertJsonApiNoPaginationLinks();
```

### `assertJsonApiPaginationLinks`

Asserts that the response has pagination links with a subset equal to the expected links provided.

```php
$response->assertJsonApiPaginationLinks([
    'first' => ...,
    'last' => ...
]);
```

### `assertJsonApiRelationshipsLinks`

Asserts that the json object specified by the path has relationships links with a subset corresponding to the expected links provided.

```php
$response->assertJsonApiPaginationLinks(
    [
        'self' => ...,
        'related' => ...
    ],
    'data.relationships.relatedList'
);
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

```sh
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

[Vincent Girol](vincent@girol.fr)

## License

This project is licensed under the [MIT](https://choosealicense.com/licenses/mit/) License.
