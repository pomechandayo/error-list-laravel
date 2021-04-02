<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;
use App\Article;
use App\Tag;

$factory->define(Article::class, 
    function(Faker $faker) {
        return [
        'title' => $faker->text(10),
        'body' => $faker->text(50),
        'user_id' =>$faker->numberBetween(1,3),
    ];
});

$factory->define(Tag::class,
    function(Faker $faker){
        $tags = ['PHP','Go','Python','Mysql','laravel'];
        $tag = array_rand($tags,1);
        return['name' => $tags[$tag],
    ];
});
