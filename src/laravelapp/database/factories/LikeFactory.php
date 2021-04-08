<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Like;
use App\Article;
use App\User;

use Faker\Generator as Faker;

$factory->define(Like::class, function (Faker $faker) {
    return [
        'article_id' => $this->faker->numberBetween(1,10),
        'user_id' => $this->faker->numberBetween(1,3),
    ];
});
