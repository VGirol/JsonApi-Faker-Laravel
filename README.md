# JsonApi-Faker-Laravel

[![Build Status](https://travis-ci.org/VGirol/JsonApi-Faker-Laravel.svg?branch=master)](https://travis-ci.org/VGirol/JsonApi-Faker-Laravel)
[![Code Coverage](https://scrutinizer-ci.com/g/VGirol/JsonApi-Faker-Laravel/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/VGirol/JsonApi-Faker-Laravel/?branch=master)
[![Infection MSI](https://badge.stryker-mutator.io/github.com/VGirol/JsonApi-Faker-Laravel/master)](https://infection.github.io)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/VGirol/JsonApi-Faker-Laravel/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/VGirol/JsonApi-Faker-Laravel/?branch=master)

This package provides a set of factories to build fake data using Laravel and the [JSON:API specification](https://jsonapi.org/).

## Technologies

- PHP 7.2+
- Laravel 5.8+

## Installation

To install through composer, simply put the following in your `composer.json` file:

```json
{
    "require-dev": {
        "vgirol/jsonapi-faker-laravel": "dev-master"
    }
}
```

And then run `composer install` from the terminal.

### Quick Installation

Above installation can also be simplified by using the following command:

```sh
composer require vgirol/jsonapi-faker-laravel
```

## Usage

First create a faker generator.

```php
use VGirol\JsonApiFaker\Laravel\Generator as JsonApiFaker;

$faker = new JsonApiFaker;
```

Then create a model and a factory.

```php
$model = new DummyModel();
$factory = $faker->resourceObject($model, 'resourceType');
```

Next you can fill the factory ...

```php
$factory->setMeta([
            'key1' => 'meta1'
        ])
        ->addLink('self', 'url');
```

Finally export as an array or as JSON.

```php
$array = $factory->toArray();
$json = $factory->toJson();
```

All these instructions can be chained.

```php
use VGirol\JsonApiFaker\Generator as JsonApiFaker;

$model = new DummyModel();
$json = new JsonApiFaker()
    ->resourceObject($model, 'resourceType')
    ->setMeta([
            'key1' => 'meta1'
        ])
    ->toJson();
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

```sh
composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

[Vincent Girol](mailto:vincent@girol.fr)

## License

This project is licensed under the [MIT](https://choosealicense.com/licenses/mit/) License.
