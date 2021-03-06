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
use Payroll\Factory\Model\Employee;

$factory->define(Payroll\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

$factory->define(Payroll\Employee::class, function (Faker\Generator $faker, array $attributes) {
    $paymentClassification = (array_get($attributes, 'payment_classification')) ? $attributes['payment_classification'] : 'SALARIED';
    $data = [
        'name' => $faker->name,
        'address' => $faker->address,
        'payment_classification' => $paymentClassification,
        'payment_method' => Payroll\Factory\PaymentMethod\Factory::HOLD_METHOD
    ];

    switch ($paymentClassification) {
        case Employee::SALARIED:
            $data['salary'] = $faker->randomFloat(2, 1500, 3000);
            break;

        case Employee::HOURLY:
            $data['hourly_rate'] = (array_get($attributes, 'hourlyRate')) ? $attributes['hourlyRate'] : $faker->randomFloat(2, 15, 30);
            break;

        case Employee::COMMISSION:
            $data['salary'] = $faker->randomFloat(2, 1500, 3000);
            $data['commission_rate'] = (array_get($attributes, 'commissionRate')) ? $attributes['commissionRate'] : $faker->randomFloat(2, 10, 20);
            break;
    }

    return $data;
});

$factory->define(Payroll\TimeCard::class, function (Faker\Generator $faker, array $attributes) {
    return [
        'employee_id' => $attributes['employee_id'],
        'date' => $attributes['date'],
        'hours' => $attributes['hours'],
    ];
});

$factory->define(Payroll\SalesReceipt::class, function (Faker\Generator $faker, array $attributes) {
    return [
        'employee_id' => $attributes['employee_id'],
        'date' => $attributes['date'],
        'amount' => $attributes['amount'],
    ];
});