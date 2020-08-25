<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Post;
use Faker\Generator as Faker;

$factory->define(Post::class, function (Faker $faker) {
    $title = $faker->sentence($nbWords = 3, $variableNbWords = true);
    return [
        'user_id' 		=> rand(1,5),
        'category_id' 	=> rand(1,20),
        'name' 			=> $title,
        'slug' 			=> str_slug($title),
        'excerpt' 		=> $faker->text(200),
        'body' 			=> $faker->text(500),
        'file' 			=> $faker->imageUrl($width = 1200, $height = 400),
        'status'        => $faker->randomElement(['DRAFT', 'PUBLISHED'])
    ];
});
