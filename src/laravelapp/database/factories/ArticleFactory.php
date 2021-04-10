<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Illuminate\Database\Eloquent\Factory;
use Faker\Generator as Faker;
use App\Article;
use App\Tag;
use App\User;


$factory->define(Article::class, 
    function(Faker $faker) {
        return [
        'title' => $this->faker->text(10),
        'body' => $this->faker->text(50),
        'status' => $this->faker->randomElement([1,1,1,1]),
        // 'user_id' => $this->faker->numberBetween(1,3),
        'user_id' => function(){
          return factory(App\User::class)->create()->id;
        },
    ];
});

$factory->define(Tag::class,
    function(Faker $faker){
        $tags = 
        ['PHP','Go','Python','Mysql','laravel', 'Ruby','Docker','javascript','AWS','Linux','Java','C',
        ];
        $tag = array_rand($tags,1);
        return['name' => $tags[$tag],
    ];
});