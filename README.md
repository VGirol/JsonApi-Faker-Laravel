# JsonApi-Faker-Laravel

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Infection MSI][ico-mutation]][link-mutation]
[![Total Downloads][ico-downloads]][link-downloads]

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

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Testing

``` bash
composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CODE_OF_CONDUCT](CODE_OF_CONDUCT.md) for details.

## Security

If you discover any security related issues, please email [vincent@girol.fr](mailto:vincent@girol.fr) instead of using the issue tracker.

## Credits

- [Girol Vincent][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/VGirol/JsonApi-Faker-Laravel.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/VGirol/JsonApi-Faker-Laravel/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/VGirol/JsonApi-Faker-Laravel.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/VGirol/JsonApi-Faker-Laravel.svg?style=flat-square
[ico-mutation]: https://badge.stryker-mutator.io/github.com/VGirol/JsonApi-Faker-Laravel/master
[ico-downloads]: https://img.shields.io/packagist/dt/VGirol/JsonApi-Faker-Laravel.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/VGirol/JsonApi-Faker-Laravel
[link-travis]: https://travis-ci.org/VGirol/JsonApi-Faker-Laravel
[link-scrutinizer]: https://scrutinizer-ci.com/g/VGirol/JsonApi-Faker-Laravel/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/VGirol/JsonApi-Faker-Laravel
[link-downloads]: https://packagist.org/packages/VGirol/JsonApi-Faker-Laravel
[link-author]: https://github.com/VGirol
[link-mutation]: https://infection.github.io
[link-contributors]: ../../contributors
