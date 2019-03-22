# JsonApi-Assert-Laravel

This package adds a lot of methods to the [`Illuminate\Foundation\Testing\TestResponse`](https://laravel.com/api/5.8/Illuminate/Foundation/Testing/TestResponse.html) class for testing APIs that implements the [JSON:API specification](https://jsonapi.org/).

## Table of content

- [JsonApi-Assert-Laravel](#jsonapi-assert-laravel)
  - [Table of content](#table-of-content)
  - [Technologies](#technologies)
  - [Installation](#installation)
    - [Quick Installation](#quick-installation)
    - [Registration](#registration)
  - [Usage](#usage)
  - [Macros (`Illuminate\Foundation\Testing\TestResponse`)](#macros-illuminatefoundationtestingtestresponse)
    - [`assertJsonApiCreated`](#assertjsonapicreated)
    - [`assertJsonApiDeleted`](#assertjsonapideleted)
    - [`assertJsonApiErrorResponse`](#assertjsonapierrorresponse)
    - [`assertJsonApiErrors`](#assertjsonapierrors)
    - [`assertJsonApiFetchedSingleResource`](#assertjsonapifetchedsingleresource)
    - [`assertJsonApiFetchedResourceCollection`](#assertjsonapifetchedresourcecollection)
    - [`assertJsonApiFetchedToOneRelationships`](#assertjsonapifetchedtoonerelationships)
    - [`assertJsonApiFetchedToManyRelationships`](#assertjsonapifetchedtomanyrelationships)
    - [`assertJsonApiNoContent`](#assertjsonapinocontent)
    - [`assertJsonApiNoPaginationLinks`](#assertjsonapinopaginationlinks)
    - [`assertJsonApiPaginationLinks`](#assertjsonapipaginationlinks)
    - [`assertJsonApiRelationshipsLinks`](#assertjsonapirelationshipslinks)
    - [`assertJsonApiResponse400`](#assertjsonapiresponse400)
    - [`assertJsonApiResponse403`](#assertjsonapiresponse403)
    - [`assertJsonApiResponse404`](#assertjsonapiresponse404)
    - [`assertJsonApiResponse406`](#assertjsonapiresponse406)
    - [`assertJsonApiResponse409`](#assertjsonapiresponse409)
    - [`assertJsonApiResponse415`](#assertjsonapiresponse415)
    - [`assertJsonApiUpdated`](#assertjsonapiupdated)
  - [Changelog](#changelog)
  - [Testing](#testing)
  - [Contributing](#contributing)
  - [Credits](#credits)
  - [License](#license)

## Technologies

- PHP 7.0+
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

TODO

## Macros (`Illuminate\Foundation\Testing\TestResponse`)

### `assertJsonApiCreated`

Asserts that the response has :
- `201 Created` status code
- header `Content-Type: application/vnd.api+json` without any media type parameters
- content with primary data corresponding to the provided model and resource type.

If the resource object returned by the response contains a self key in its links member and a Location header is provided, this methods asserts that the value of the self member matches the value of the Location header.

```php
use Illuminate\Database\Eloquent\Model;

$model = new Model();
$response->assertJsonApiCreated($model, 'resourceType');
```

### `assertJsonApiDeleted`

Asserts that the response has :
- `200 OK` status code
- header `Content-Type: application/vnd.api+json` without any media type parameters
- content with only meta or jsonapi members.

```php
$response->assertJsonApiDeleted();
```

### `assertJsonApiErrorResponse`

Asserts that the response has :
- the given status code
- header `Content-Type: application/vnd.api+json` without any media type parameters
- content with errors object that contains a subset corresponding to the provided error objects.

```php
$response->assertJsonApiErrorResponse(
    404,
    [
        [
            'status' => '404',
            'title' => 'Not Found'
        ]
    ]
);
```

### `assertJsonApiErrors`

Asserts that the response has content with errors object that contains a subset corresponding to the provided error objects.

```php
$response->assertJsonApiErrors(
    [
        [
            'status' => '406',
            'title' => 'Not Acceptable'
        ],
        [
            'status' => '415',
            'title' => 'Unsupported Media Type'
        ]
    ]
);
```

### `assertJsonApiFetchedSingleResource`

Asserts that the response has :
- `200 OK` status code
- header `Content-Type: application/vnd.api+json` without any media type parameters
- content with primary data corresponding to the provided model and resource type.

```php
use Illuminate\Database\Eloquent\Model;

$model = new Model();
$response->assertJsonApiFetchedSingleResource($model, 'resourceType');
```

### `assertJsonApiFetchedResourceCollection`

Asserts that the response has :
- `200 OK` status code
- header `Content-Type: application/vnd.api+json` without any media type parameters
- content with primary data corresponding to the provided collection.

```php
use Illuminate\Database\Eloquent\Collection;

$collection = new Collection([...]);
$options = [];
$response->assertJsonApiFetchedResourceCollection($collection, $options);
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
use Illuminate\Database\Eloquent\Collection;

$collection = new Collection([...]);
$response->assertJsonApiFetchedToManyRelationships($collection, 'resourceType');
```

### `assertJsonApiNoContent`

Asserts that the response has 204 status code and no content.

Asserts that the response has :
- `204 No Content` status code
- header `Content-Type: application/vnd.api+json` without any media type parameters
- no content

```php
$response->assertJsonApiNoContent();
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

### `assertJsonApiResponse400`

Asserts that the response has :
- `400 Bad Request` status code
- header `Content-Type: application/vnd.api+json` without any media type parameters
- content with errors object that contains a subset corresponding to the provided error objects.

```php
$response->assertJsonApiResponse400(
    [
        [
            'status' => '400',
            'title' => 'Bad Request',
            'details' => ...
        ]
    ]
);
```

### `assertJsonApiResponse403`

Asserts that the response has :
- `403 Forbidden` status code
- header `Content-Type: application/vnd.api+json` without any media type parameters
- content with errors object that contains a subset corresponding to the provided error objects.

```php
$response->assertJsonApiResponse403(
    [
        [
            'status' => '403',
            'title' => 'Forbidden',
            'details' => ...
        ]
    ]
);
```

### `assertJsonApiResponse404`

Asserts that the response has :
- `404 Not Found` status code
- header `Content-Type: application/vnd.api+json` without any media type parameters
- content with errors object that contains a subset corresponding to the provided error objects.

```php
$response->assertJsonApiResponse404(
    [
        [
            'status' => '404',
            'title' => 'Not Found',
            'details' => ...
        ]
    ]
);
```

### `assertJsonApiResponse406`

Asserts that the response has :
- `406 Not Acceptable` status code
- header `Content-Type: application/vnd.api+json` without any media type parameters
- content with errors object that contains a subset corresponding to the provided error objects.

```php
$response->assertJsonApiResponse406(
    [
        [
            'status' => '406',
            'title' => 'Not Acceptable',
            'details' => ...
        ]
    ]
);
```

### `assertJsonApiResponse409`

Asserts that the response has :
- `409 Conflict` status code
- header `Content-Type: application/vnd.api+json` without any media type parameters
- content with errors object that contains a subset corresponding to the provided error objects.

```php
$response->assertJsonApiResponse409(
    [
        [
            'status' => '409',
            'title' => 'Conflict',
            'details' => ...
        ]
    ]
);
```

### `assertJsonApiResponse415`

Asserts that the response has :
- `415 Unsupported Media Type` status code
- header `Content-Type: application/vnd.api+json` without any media type parameters
- content with errors object that contains a subset corresponding to the provided error objects.

```php
$response->assertJsonApiResponse415(
    [
        [
            'status' => '415',
            'title' => 'Unsupported Media Type',
            'details' => ...
        ]
    ]
);
```

### `assertJsonApiUpdated`
Asserts that the response has 200 status code and content with primary data corresponding to the  model if provided, or only "meta" member otherwise.


Asserts that the response has :
- `200 OK` status code
- header `Content-Type: application/vnd.api+json` without any media type parameters
- content with primary data corresponding to the model and resource type if provided, or only meta member otherwise.

```php
use Illuminate\Database\Eloquent\Model;

$model = new Model();
$response->assertJsonApiUpdated($model, 'resourceType');
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