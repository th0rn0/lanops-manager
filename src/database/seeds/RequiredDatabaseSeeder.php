<?php

use Illuminate\Database\Seeder;

use Faker\Factory as Faker;

class RequiredDatabaseSeeder extends Seeder
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

        ## House Cleaning
        // DB::table('settings')->truncate();

        ## Settings
        App\Setting::firstOrCreate(
            ['setting'          => 'org_name'],
            [
                'value'         => env('ORG_NAME', 'OrgNameHere'),
                'default'       => true,
                'description'   => 'Name of the Organization'
            ]
        );
        App\Setting::firstOrCreate(
            ['setting'          => 'org_tagline'],
            [
                'value'         => env('ORG_TAGLINE', 'Tagline Here'),
                'default'       => true,
                'description'   => 'Tagline of the Organization'
            ]
        );
        App\Setting::firstOrCreate(
            ['setting'          => 'org_logo'],
            [
                'value'         => env('ORG_LOGO', '/storage/images/main/logo_main.png'),
                'default'       => true,
                'description'   => 'Organization Logo'
            ]
        );
        App\Setting::firstOrCreate(
            ['setting'          => 'org_favicon'],
            [
                'value'         => env('ORG_FAVICON', '/storage/images/main/favicon.ico'),
                'default'       => true,
                'description'   => 'Organization Favicon'
            ]
        );
        App\Setting::firstOrCreate(
            ['setting'          => 'purchase_terms_and_conditions'],
            [
                'value'         => $faker->paragraph($nbSentences = 90, $variableNbSentences = true),
                'default'       => true,
                'description'   => 'T&Cs to be displayed on the checkout page'
            ]
        );
        App\Setting::firstOrCreate(
            ['setting'          => 'registration_terms_and_conditions'],
            [
                'value'         => $faker->paragraph($nbSentences = 90, $variableNbSentences = true),
                'default'       => true,
                'description'   => 'T&Cs to be displayed on the registration page'
            ]
        );
        App\Setting::firstOrCreate(
            ['setting'          => 'steam_link'],
            [
                'value'         => null,
                'default'       => true,
                'description'   => 'Link to your Steam Group'
            ]
        );
        App\Setting::firstOrCreate(
            ['setting'          => 'teamspeak_link'],
            [
                'value'         => null,
                'default'       => true,
                'description'   => 'IP to your Teamspeak Server'
            ]
        );
        App\Setting::firstOrCreate(
            ['setting'          => 'discord_id'],
            [
                'value'         => null,
                'default'       => true,
                'description'   => 'ID for Discord Widget'
            ]
        );
        App\Setting::firstOrCreate(
            ['setting'          => 'discord_link'],
            [
                'value'         => null,
                'default'       => true,
                'description'   => 'Link to your Discord Server'
            ]
        );
        App\Setting::firstOrCreate(
            ['setting'          => 'reddit_link'],
            [
                'value'         => null,
                'default'       => true,
                'description'   => 'Link to your Subreddit'
            ]
        );
        App\Setting::firstOrCreate(
            ['setting'          => 'facebook_link'],
            [
                'value'         => null,
                'default'       => true,
                'description'   => 'Link to your Facebook Page'
            ]
        );
        App\Setting::firstOrCreate(
            ['setting'          => 'frontpage_alot_tagline'],
            [
                'value'         => "People we've fragged",
                'default'       => true,
                'description'   => "Appears on the frontpage on the banner above the footer"
            ]
        );
        App\Setting::firstOrCreate(
            ['setting'          => 'participant_count_offset'],
            [
                'value'         => 0,
                'default'       => true,
                'description'   => 'Increment the Total Participant Count on the Home page'
            ]
        );
        App\Setting::firstOrCreate(
            ['setting'          => 'event_count_offset'],
            [
                'value'         => 0,
                'default'       => true,
                'description'   => 'Increment the Total Event Count on the Home page'
            ]
        );
        App\Setting::firstOrCreate(
            ['setting'          => 'currency'],
            [
                'value'         => 'GBP',
                'default'       => true,
                'description'   => 'Currency to use site wide. Only one can be used'
            ]
        );
        App\Setting::firstOrCreate(
            ['setting'          => 'about_main'],
            [
                'value'         => $faker->paragraph($nbSentences = 90, $variableNbSentences = true),
                'default'       => true,
            ]
        );
        App\Setting::firstOrCreate(
            ['setting'          => 'about_short'],
            [
                'value'         => $faker->paragraph($nbSentences = 4, $variableNbSentences = true),
                'default'       => true,
            ]
        );
        App\Setting::firstOrCreate(
            ['setting'          => 'about_our_aim'],
            [
                'value'         => $faker->paragraph($nbSentences = 90, $variableNbSentences = true),
                'default'       => true,
            ]
        );
        App\Setting::firstOrCreate(
            ['setting'          => 'about_who'],
            [
                'value'         => $faker->paragraph($nbSentences = 90, $variableNbSentences = true),
                'default'       => true,
            ]
        );
        App\Setting::firstOrCreate(
            ['setting'          => 'social_facebook_page_access_token'],
            [
                'value'         => null,
                'default'       => true,
            ]
        );
        App\Setting::firstOrCreate(
            ['setting'          => 'payment_gateway_stripe'],
            [
                'value'         => true,
                'default'       => true,
            ]
        );
        App\Setting::firstOrCreate(
            ['setting'          => 'payment_gateway_paypal_express'],
            [
                'value'         => true,
                'default'       => true,
            ]
        );
        App\Setting::firstOrCreate(
            ['setting'          => 'credit_enabled'],
            [
                'value'         => true,
                'default'       => true,
            ]
        );
        App\Setting::firstOrCreate(
            ['setting'          => 'credit_award_tournament_participation'],
            [
                'value'         => 0,
                'default'       => true,
            ]
        );
        App\Setting::firstOrCreate(
            ['setting'          => 'credit_award_tournament_first'],
            [
                'value'         => 0,
                'default'       => true,
            ]
        );
        App\Setting::firstOrCreate(
            ['setting'          => 'credit_award_tournament_second'],
            [
                'value'         => 0,
                'default'       => true,
            ]
        );
        App\Setting::firstOrCreate(
            ['setting'          => 'credit_award_tournament_third'],
            [
                'value'         => 0,
                'default'       => true,
            ]
        );
        App\Setting::firstOrCreate(
            ['setting'          => 'credit_award_registration_event'],
            [
                'value'         => 0,
                'default'       => true,
            ]
        );
        App\Setting::firstOrCreate(
            ['setting'          => 'credit_award_registration_site'],
            [
                'value'         => 0,
                'default'       => true,
            ]
        );
        App\Setting::firstOrCreate(
            ['setting'          => 'login_standard'],
            [
                'value'         => true,
                'default'       => true,
            ]
        );
        App\Setting::firstOrCreate(
            ['setting'          => 'login_steam'],
            [
                'value'         => false,
                'default'       => true,
            ]
        );
        App\Setting::firstOrCreate(
            ['setting'          => 'shop_status'],
            [
                'value'         => 'OPEN',
                'default'       => true,
            ]
        );
        App\Setting::firstOrCreate(
            ['setting'          => 'shop_welcome_message'],
            [
                'value'         => "Welcome to the Shop!",
                'default'       => true,
            ]
        );
        App\Setting::firstOrCreate(
            ['setting'          => 'shop_closed_message'],
            [
                'value'         => "Shop is currently closed!",
                'default'       => true,
            ]
        );
        App\Setting::firstOrCreate(
            ['setting'          => 'shop_enabled'],
            [
                'value'         => false,
                'default'       => true,
            ]
        );

        App\Appearance::firstOrCreate(
            ['key'   => 'color_primary'],
            [
                'value' => 'orange',
                'type'  => 'CSS_VAR',
            ]
        );

        App\Appearance::firstOrCreate(
            ['key'   => 'color_primary_text'],
            [
                'value' => 'white',
                'type'  => 'CSS_VAR',
            ]
        );

        App\Appearance::firstOrCreate(
            ['key'   => 'color_secondary'],
            [
                'value' => '#333',
                'type'  => 'CSS_VAR',
            ]
        );

        App\Appearance::firstOrCreate(
            ['key'   => 'color_secondary_text'],
            [
                'value' => 'white',
                'type'  => 'CSS_VAR',
            ]
        );

        App\Appearance::firstOrCreate(
            ['key'   => 'color_body_links'],
            [
                'value' => 'orange',
                'type'  => 'CSS_VAR',
            ]
        );

        App\Appearance::firstOrCreate(
            ['key'   => 'color_body_background'],
            [
                'value' => '#fff',
                'type'  => 'CSS_VAR',
            ]
        );

        App\Appearance::firstOrCreate(
            ['key'   => 'color_body_text'],
            [
                'value' => '#333',
                'type'  => 'CSS_VAR',
            ]
        );

        App\Appearance::firstOrCreate(
            ['key'   => 'color_header_background'],
            [
                'value' => '#333',
                'type'  => 'CSS_VAR',
            ]
        );

        App\Appearance::firstOrCreate(
            ['key'   => 'color_header_text'],
            [
                'value' => '#9d9d9d',
                'type'  => 'CSS_VAR',
            ]
        );

        App\SliderImage::firstOrCreate(
            ['slider_name'   => 'frontpage'],
            [
            	'path'  => '/storage/images/main/slider/frontpage/1.jpg',
            	'order' => '4',
            ]
        );

        App\SliderImage::firstOrCreate(
            ['slider_name'   => 'frontpage'],
            [
            	'path'  => '/storage/images/main/slider/frontpage/2.jpg',
            	'order' => '1',
            ]
        );

        App\SliderImage::firstOrCreate(
            ['slider_name'   => 'frontpage'],
            [
            	'path'  => '/storage/images/main/slider/frontpage/3.jpg',
            	'order' => '2',
            ]
        );

        App\SliderImage::firstOrCreate(
            ['slider_name'   => 'frontpage'],
            [
            	'path'  => '/storage/images/main/slider/frontpage/4.jpg',
            	'order' => '5',
            ]
        );

        App\SliderImage::firstOrCreate(
            ['slider_name'   => 'frontpage'],
            [
            	'path'  => '/storage/images/main/slider/frontpage/5.jpg',
            	'order' => '3',
            ]
        );
    }
}


