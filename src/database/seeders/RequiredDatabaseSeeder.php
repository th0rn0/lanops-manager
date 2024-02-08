<?php

namespace Database\Seeders;

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
                'value'         => env('APP_NAME', 'OrgNameHere'),
                'default'       => true,
                'description'   => 'Name of the Organization'
            ]
        );
        App\Setting::firstOrCreate(
            ['setting'          => 'org_tagline'],
            [
                'value'         => env('APP_TAGLINE', 'Tagline Here'),
                'default'       => true,
                'description'   => 'Tagline of the Organization'
            ]
        );
        App\Setting::firstOrCreate(
            ['setting'          => 'seo_keywords'],
            [
                'value'         => env('SEO_KEYWORDS', "Events,Eventula,LanOps,Th0rn0"),
                'default'       => true,
                'description'   => 'Keywords for the Organization SEO'
            ]
        );
        App\Setting::firstOrCreate(
            ['setting'          => 'org_logo'],
            [
                'value'         => env('APP_LOGO', '/storage/images/main/logo_main.png'),
                'default'       => true,
                'description'   => 'Organization Logo'
            ]
        );
        App\Setting::firstOrCreate(
            ['setting'          => 'org_favicon'],
            [
                'value'         => env('APP_FAVICON', '/storage/images/main/favicon.ico'),
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
            ['setting'          => 'installed'],
            [
                'value'         => false,
                'default'       => true,
            ]
        );

        ## Apperance
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
    }
}
