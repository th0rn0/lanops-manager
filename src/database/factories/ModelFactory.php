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

$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'name'              => $faker->name,
        'email'             => $faker->email,
        'password'          => bcrypt(str_random(10)),
        'remember_token'    => str_random(10),
    ];
});

## Events

$factory->define(App\Event::class, function (Faker\Generator $faker) {
	$event_name = 'EventNameHere ' . $faker->randomDigitNotNull;
    $start_date = date_format($faker->dateTimeBetween('+1 months', '+2 months'),"Y-m-d");
    $end_date   = date('Y-m-d', strtotime($start_date. ' + 2 days'));
    return [
        'display_name'      => $event_name,
        'nice_name'         => strtolower(str_replace(' ', '-', $event_name)),
        'slug'              => strtolower(str_replace(' ', '-', $event_name)),
        'start'             => $start_date . ' 16:00:00',
        'end'               => $end_date . ' 18:00:00',
        'desc_long'         => $faker->sentences($nb = 5, $asText = true),
        'desc_short'        => $faker->sentences($nb = 1, $asText = true),
        'status'            => 'published',
    ];
});

## Event Tickets

$factory->define(App\EventTicket::class, function (Faker\Generator $faker) {
    return [
        'name'          => 'Weekend Ticket',
        'type'          => 'weekend',
        'price'         => '30',
        'seatable'      => true,
        'sale_start'    => NULL,
        'sale_end'      => NULL,
    ];
});

## Event Timetable

$factory->define(App\EventTimetable::class, function (Faker\Generator $faker) {
    return [
        'name'  => $faker->words($nb = 3, $asText = true),
        'status'        => 'published',
    ];
});

## Event Timetable Data

## Event Seating

$factory->define(App\EventSeatingPlan::class, function (Faker\Generator $faker) {
    return [
        'name'      => $faker->words($nb = 3, $asText = true),
        'columns'   => 8,
        'rows'      => 6,
        'headers'   => 'A,B,C,D,E,F,G,H',
        'status'    => 'published',
    ];
});

## Venue

$factory->define(App\EventVenue::class, function (Faker\Generator $faker) {
    $venue_name = 'VenueNameHere ' . $faker->randomDigitNotNull;
    return [
        'display_name'      => $venue_name,
        'slug'              => strtolower(str_replace(' ', '-', $venue_name)),
        'address_1'         => $faker->secondaryAddress(),
        'address_street'    => $faker->streetName(),
        'address_city'      => $faker->city(),
        'address_postcode'  => $faker->postcode(),
        'address_country'   => $faker->country(),
    ];
});

## Event Information
$factory->define(App\EventInformation::class, function (Faker\Generator $faker) {
    return [
        'title' => $faker->words($nb = 3, $asText = true),
        'text'  => $faker->paragraphs($nb = 3, $asText = true),
    ];
});

## Settings
$factory->define(App\Setting::class, function (Faker\Generator $faker) {
    return [
    ];
});

## Games
$factory->define(App\Game::class, function (Faker\Generator $faker) {
    return [
    ];
});

## News Article
$factory->define(App\NewsArticle::class, function (Faker\Generator $faker) {
    return [
        'title'     => $faker->words($nb = 3, $asText = true),
        'text'      => $faker->paragraphs($nb = 3, $asText = true),
    ];
});