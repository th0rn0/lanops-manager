<?php

use Illuminate\Database\Seeder;

use Faker\Factory as Faker;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        ## House Cleaning
        \DB::table('settings')->delete();

        ## Settings
        factory(App\Setting::class)->create([
            'setting'       => 'org_name',
            'value'         => env('ORG_NAME', 'OrgNameHere'),
            'default'       => true,
            'description'   => 'Name of the Organization'
        ]);
        factory(App\Setting::class)->create([
            'setting'       => 'org_logo',
            'value'         => env('ORG_LOGO', '/storage/images/main/logo_main.png'),
            'default'       => true,
            'description'   => 'Organization Logo'
        ]);
        factory(App\Setting::class)->create([
            'setting'       => 'org_favicon',
            'value'         => env('ORG_FAVICON', '/storage/images/main/favicon.ico'),
            'default'       => true,
            'description'   => 'Organization Favicon'
        ]);
        factory(App\Setting::class)->create([
            'setting'       => 'purchase_terms_and_conditions',
            'value'         => $faker->paragraph($nbSentences = 90, $variableNbSentences = true),
            'default'       => true,
            'description'   => 'T&Cs to be displayed on the checkout page'
        ]);
        factory(App\Setting::class)->create([
            'setting'       => 'registration_terms_and_conditions',
            'value'         => $faker->paragraph($nbSentences = 90, $variableNbSentences = true),
            'default'       => true,
            'description'   => 'T&Cs to be displayed on the registration page'
        ]);
        factory(App\Setting::class)->create([
            'setting'       => 'steam_link',
            'value'         => null,
            'default'       => true,
            'description'   => 'Link to your Steam Group'
        ]);
        factory(App\Setting::class)->create([
            'setting'       => 'teamspeak_link',
            'value'         => null,
            'default'       => true,
            'description'   => 'IP to your Teamspeak Server'
        ]);
        factory(App\Setting::class)->create([
            'setting'       => 'discord_link',
            'value'         => null,
            'default'       => true,
            'description'   => 'Link to your Discord Server'
        ]);
        factory(App\Setting::class)->create([
            'setting'       => 'reddit_link',
            'value'         => null,
            'default'       => true,
            'description'   => 'Link to your Subreddit'
        ]);
        factory(App\Setting::class)->create([
            'setting'       => 'facebook_link',
            'value'         => null,
            'default'       => true,
            'description'   => 'Link to your Facebook Page'
        ]);
        factory(App\Setting::class)->create([
            'setting'       => 'participant_count_offset',
            'value'         => 0,
            'default'       => true,
            'description'   => 'Increment the Total Participant Count on the Home page'
        ]);
        factory(App\Setting::class)->create([
            'setting'       => 'lan_count_offset',
            'value'         => 0,
            'default'       => true,
            'description'   => 'Increment the Total Lan Count on the Home page'
        ]);
        factory(App\Setting::class)->create([
            'setting'       => 'currency',
            'value'         => 'GBP',
            'default'       => true,
            'description'   => 'Currency to use site wide. Only one can be used'
        ]);
        factory(App\Setting::class)->create([
            'setting'       => 'about_main',
            'value'         => $faker->paragraph($nbSentences = 90, $variableNbSentences = true),
            'default'       => true,
        ]);
        factory(App\Setting::class)->create([
            'setting'       => 'about_short',
            'value'         => $faker->paragraph($nbSentences = 4, $variableNbSentences = true),
            'default'       => true,
        ]);
        factory(App\Setting::class)->create([
            'setting'       => 'about_our_aim',
            'value'         => $faker->paragraph($nbSentences = 90, $variableNbSentences = true),
            'default'       => true,
        ]);
        factory(App\Setting::class)->create([
            'setting'       => 'about_who',
            'value'         => $faker->paragraph($nbSentences = 90, $variableNbSentences = true),
            'default'       => true,
        ]);
        factory(App\Setting::class)->create([
            'setting'       => 'social_facebook_page_access_token',
            'value'         => null,
            'default'       => true,
        ]);
        factory(App\Setting::class)->create([
            'setting'       => 'payment_gateway_stripe',
            'value'         => true,
            'default'       => true,
        ]);
        factory(App\Setting::class)->create([
            'setting'       => 'payment_gateway_paypal_express',
            'value'         => true,
            'default'       => true,
        ]);
        factory(App\Setting::class)->create([
            'setting'       => 'credit_enabled',
            'value'         => true,
            'default'       => true,
        ]);
        factory(App\Setting::class)->create([
            'setting'       => 'credit_award_tournament_participation',
            'value'         => 0,
            'default'       => true,
        ]);
        factory(App\Setting::class)->create([
            'setting'       => 'credit_award_tournament_first',
            'value'         => 0,
            'default'       => true,
        ]);
        factory(App\Setting::class)->create([
            'setting'       => 'credit_award_tournament_second',
            'value'         => 0,
            'default'       => true,
        ]);
        factory(App\Setting::class)->create([
            'setting'       => 'credit_award_tournament_third',
            'value'         => 0,
            'default'       => true,
        ]);
        factory(App\Setting::class)->create([
            'setting'       => 'credit_award_registration_event',
            'value'         => 0,
            'default'       => true,
        ]);
        factory(App\Setting::class)->create([
            'setting'       => 'credit_award_registration_site',
            'value'         => 0,
            'default'       => true,
        ]);
        factory(App\Setting::class)->create([
            'setting'       => 'shop_enabled',
            'value'         => true,
            'default'       => true,
        ]);
    }
}
