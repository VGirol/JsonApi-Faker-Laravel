<?php

use Faker\Generator as Faker;

if (!isset($factory)) {
    return;
}

$factory->define(VGirol\JsonApi\Tests\Tools\Models\RelatedModelForTest::class, function (Faker $faker) {
    return [
        'REL_ID' => $faker->unique()->randomNumber(3),
        'REL_NAME' => $faker->unique()->name,
        'REL_DATE' => $faker->date()
    ];
});
