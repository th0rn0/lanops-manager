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

$factory->define(App\Models\User::class, function (Faker\Generator $faker) {
    return [
        'name'              => $faker->name,
        'email'             => $faker->email,
        'password'          => bcrypt(str_random(10)),
        'remember_token'    => str_random(10),
    ];
});

## Events

$factory->define(App\Models\Event::class, function (Faker\Generator $faker) {
    $event_name = 'EventNameHere ' . $faker->randomDigitNotNull;
    $start_date = date_format($faker->dateTimeBetween('+1 months', '+2 months'), "Y-m-d");
    $end_date   = date('Y-m-d', strtotime($start_date. ' + 2 days'));
    return [
        'display_name'      => $event_name,
        'nice_name'         => strtolower(str_replace(' ', '-', $event_name)),
        'slug'              => strtolower(str_replace(' ', '-', $event_name)),
        'start'             => $start_date . ' 16:00:00',
        'end'               => $end_date . ' 18:00:00',
        'desc_long'         => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam aliquam lorem sit amet luctus consequat. Curabitur egestas ante ac dui molestie dignissim. Praesent at hendrerit ligula. Aenean auctor luctus augue in iaculis. Morbi tellus nibh, mollis in quam at, varius vehicula nisi. Praesent nulla diam, consequat et molestie eget, mattis ac diam. Vivamus nisi metus, rutrum non sem semper, varius blandit sapien. Duis cursus risus vitae lectus sollicitudin aliquet. Sed quis fringilla leo, et egestas arcu. Cras non diam quis lacus fermentum auctor. Cras ut ante id nibh volutpat laoreet. Proin volutpat tellus laoreet euismod vulputate.",
        'desc_short'        => "Some Awesome LAN Event",
        'status'            => 'published',
        'type'              => 'LAN'
    ];
});



## Event Tickets

$factory->define(App\Models\EventTicket::class, function (Faker\Generator $faker) {
    return [
        'name'          => 'Weekend Ticket',
        'type'          => 'weekend',
        'price'         => '30',
        'seatable'      => true,
        'sale_start'    => null,
        'sale_end'      => null,
    ];
});

## Event Timetable

$factory->define(App\Models\EventTimetable::class, function (Faker\Generator $faker) {
    return [
        'name'  => $faker->words($nb = 3, $asText = true),
        'status'        => 'published',
    ];
});

## Event Timetable Data

## Event Seating

$factory->define(App\Models\EventSeatingPlan::class, function (Faker\Generator $faker) {
    return [
        'name'      => $faker->words($nb = 3, $asText = true),
        'columns'   => 8,
        'rows'      => 6,
        'headers'   => 'A,B,C,D,E,F,G,H',
        'status'    => 'published',
    ];
});

## Venue

$factory->define(App\Models\EventVenue::class, function (Faker\Generator $faker) {
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
$factory->define(App\Models\EventInformation::class, function (Faker\Generator $faker) {
    return [
        'title' => $faker->words($nb = 3, $asText = true),
        'text'  => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam aliquam lorem sit amet luctus consequat. Curabitur egestas ante ac dui molestie dignissim. Praesent at hendrerit ligula. Aenean auctor luctus augue in iaculis. Morbi tellus nibh, mollis in quam at, varius vehicula nisi. Praesent nulla diam, consequat et molestie eget, mattis ac diam. Vivamus nisi metus, rutrum non sem semper, varius blandit sapien. Duis cursus risus vitae lectus sollicitudin aliquet. Sed quis fringilla leo, et egestas arcu. Cras non diam quis lacus fermentum auctor. Cras ut ante id nibh volutpat laoreet. Proin volutpat tellus laoreet euismod vulputate.",
    ];
});

## News Article
$factory->define(App\Models\NewsArticle::class, function (Faker\Generator $faker) {
    return [
        'title'     => $faker->words($nb = 3, $asText = true),
        'text'      => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam aliquam lorem sit amet luctus consequat. Curabitur egestas ante ac dui molestie dignissim. Praesent at hendrerit ligula. Aenean auctor luctus augue in iaculis. Morbi tellus nibh, mollis in quam at, varius vehicula nisi. Praesent nulla diam, consequat et molestie eget, mattis ac diam. Vivamus nisi metus, rutrum non sem semper, varius blandit sapien. Duis cursus risus vitae lectus sollicitudin aliquet. Sed quis fringilla leo, et egestas arcu. Cras non diam quis lacus fermentum auctor. Cras ut ante id nibh volutpat laoreet. Proin volutpat tellus laoreet euismod vulputate.",
        // 'text'      => $faker->paragraphs([$nb = 3, $asText = true]),
    ];
});
