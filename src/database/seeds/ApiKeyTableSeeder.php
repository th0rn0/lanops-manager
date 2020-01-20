<?php

use Illuminate\Database\Seeder;

use Faker\Factory as Faker;

class ApiKeyTableSeeder extends Seeder
{
    private $settings = [

    ];
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        ## Api Keys
        App\ApiKey::firstOrCreate(
            ['key'          => 'paypal_username'],
            [
                'value'         => env('PAYPAL_USERNAME', null),
            ]
        );
        App\ApiKey::firstOrCreate(
            ['key'          => 'paypal_password'],
            [
                'value'         => env('PAYPAL_PASSWORD', null),
            ]
        );
        App\ApiKey::firstOrCreate(
            ['key'          => 'paypal_signature'],
            [
                'value'         => env('PAYPAL_SIGNATURE', null),
            ]
        );
        App\ApiKey::firstOrCreate(
            ['key'          => 'stripe_public_key'],
            [
                'value'         => env('STRIPE_PUBLIC_KEY', null),
            ]
        );
        App\ApiKey::firstOrCreate(
            ['key'          => 'stripe_secret_key'],
            [
                'value'         => env('STRIPE_SECRET_KEY', null),
            ]
        );
    }
}


