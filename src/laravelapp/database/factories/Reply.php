<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;
use App\Reply;

$factory->define(Reply::class, function (Faker $faker) {
    return [
        'user_id' => $this->faker->numberBetween(1,3),
        'comment_id' => $this->faker->numberBetween(1,10),
        'body' => $this->faker->realtext(10),
    ];
});
