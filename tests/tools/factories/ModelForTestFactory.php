<?php

use Faker\Generator as Faker;

if (!isset($factory)) {
    return;
}

$factory->define(VGirol\JsonApi\Tests\Tools\Models\ModelForTest::class, function (Faker $faker) {
    return [
        'TST_ID' => $faker->unique()->randomNumber(3),
        'TST_NAME' => $faker->unique()->name,
        'TST_NUMBER' => $faker->randomNumber(9),
        'TST_CREATION_DATE' => $faker->date()
    ];
});
