<?php

/** @var  \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Charity;
use Faker\Generator as Faker;


$factory->define(Charity::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'description' => $faker->text(1024),
        'image' =>   'https://source.unsplash.com/random',
        'created_by' => $faker->optional()->randomDigit,
    ];
});
