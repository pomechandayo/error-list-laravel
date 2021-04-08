<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Comment;
use App\Article;
use Faker\Generator as Faker;

$factory->define(Comment::class, function (Faker $faker) {
    return [
        'article_id' => $this->faker->numberBetween(1,10),
        'user_id' => $this->faker->numberBetween(1,3),
        'body' => $this->faker->realText(20),
    ];
});
