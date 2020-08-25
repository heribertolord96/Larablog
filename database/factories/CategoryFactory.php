<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Category;
use Faker\Generator as Faker;
$title = 
$factory->define(Category::class, function (Faker $faker) {
    $title = $faker->sentence($nbWords = 3, $variableNbWords = true);
    return [
        'name' => $title, 
        'slug' => str_slug($title),
        'body' => $faker->text(500),
    ];
});
