<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Post;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(Post::class, function (Faker $faker) {
    $name = $faker->realText(rand(70, 100));
    $createdAt = $faker->dateTimeBetween('-2 months', '- 5 days');
    return [
        'user_id' => rand(1, 10),
        'category_id' => rand(1, 12),
        'name' => $name,
        'excerpt' => $faker->realText(rand(300, 400)),
        'content' => $faker->realText(rand(400, 500)),
        'slug' => Str::slug($name),
        'published_by' => 1,
        'created_at' => $createdAt,
        'updated_at' => $createdAt
    ];
});
