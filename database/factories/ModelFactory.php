<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */


$factory->define(App\Client::class, function (Faker\Generator $faker) {

    return [
        'one_c_id' => $faker -> randomDigitNotNull,
        'name' => $faker->company,
        'code' => $faker->title,
        'manager_id' => null,
        'master_id' => null,
        'ancestor_id' => null,
        'root_id' => null,
        'specification_id' => null,
        'phone_number' => $faker->phoneNumber,
        'address' => $faker->address,
        'main_contractor' => $faker->company,
        'organization' => $faker->company,
    ];
});

$factory->define(App\Role::class, function (Faker\Generator $faker) {

    return [
        'name' => 'company_admin',

    ];
});
