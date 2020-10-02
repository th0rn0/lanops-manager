<?php

namespace Database\Seeders;

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

        \DB::table('api_keys')->delete();
        
        ## Api Keys
        factory(App\ApiKey::class)->create([
            'key'          => 'paypal_username',
            'value'         => env('PAYPAL_USERNAME', null),
        ]);
       factory(App\ApiKey::class)->create([
            'key'          => 'paypal_password',
            'value'         => env('PAYPAL_PASSWORD', null),
        ]);
        factory(App\ApiKey::class)->create([
            'key'          => 'paypal_signature',
            'value'         => env('PAYPAL_SIGNATURE', null),
        ]);
        factory(App\ApiKey::class)->create([
            'key'          => 'stripe_public_key',
            'value'         => env('STRIPE_PUBLIC_KEY', null),
        ]);
        factory(App\ApiKey::class)->create([
            'key'          => 'stripe_secret_key',
            'value'         => env('STRIPE_SECRET_KEY', null),
        ]);
        factory(App\ApiKey::class)->create([
            'key'          => 'facebook_app_id',
            'value'         => env('FACEBOOK_APP_ID', null),
        ]);
        factory(App\ApiKey::class)->create([
            'key'          => 'facebook_app_secret',
            'value'         => env('FACEBOOK_APP_SECRET', null),
        ]);
        factory(App\ApiKey::class)->create([
            'key'          => 'challonge_api_key',
            'value'         => env('CHALLONGE_API_KEY', null),
        ]);
        factory(App\ApiKey::class)->create([
            'key'          => 'google_analytics_tracking_id',
            'value'         => env('GOOGLE_ANALYTICS_TRACKING_ID', null),
        ]);
        factory(App\ApiKey::class)->create([
            'key'          => 'facebook_pixel_id',
            'value'         => env('FACEBOOK_PIXEL_ID', null),
        ]);
        factory(App\ApiKey::class)->create([
            'key'          => 'steam_api_key',
            'value'         => env('STEAM_API_KEY', null),
        ]);
    }
}


