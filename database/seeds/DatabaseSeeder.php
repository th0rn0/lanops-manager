<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ## House Cleaning
        \DB::table('events')->delete();
        \DB::table('event_tickets')->delete();
        \DB::table('event_timetables')->delete();
        \DB::table('event_venues')->delete();
        \DB::table('event_information')->delete();
        \DB::table('settings')->delete();

        ## Venue
        $venue = factory(App\EventVenue::class)->create();

        ## Events
        factory(App\Event::class)->create([
            'event_venue_id'    => $venue->id,
            'status'            => 'PUBLISHED',
        ])->each(
            function ($event) {
                factory(App\EventTicket::class)->create([
                    'event_id' => $event->id,
                ]);
                factory(App\EventTimetable::class)->create([
                    'event_id' => $event->id,
                ]);
                factory(App\EventInformation::class, 5)->create([
                    'event_id' => $event->id,
                ]);
                factory(App\EventSeatingPlan::class)->create([
                    'event_id' => $event->id,
                ]);
            }
        );

        ## Settings
        factory(App\Setting::class)->create([
            'setting'   => 'org_name',
            'value'     => env('ORG_NAME', 'OrgNameHere'),
            'default'   => true,
        ]);
        factory(App\Setting::class)->create([
            'setting'   => 'org_logo',
            'value'     => env('ORG_LOGO', '/storage/images/main/logo_main.png'),
            'default'   => true,
        ]);
        factory(App\Setting::class)->create([
            'setting'   => 'org_favicon',
            'value'     => env('ORG_FAVICON', '/storage/images/main/favicon.png'),
            'default'   => true,
        ]);
        factory(App\Setting::class)->create([
            'setting'   => 'terms_and_conditions',
            'value'     => 'these are Terms & Conditions',
            'default'   => true,
        ]);
        factory(App\Setting::class)->create([
            'setting'   => 'steam',
            'value'     => null,
            'default'   => true,
        ]);
        factory(App\Setting::class)->create([
            'setting'   => 'teamspeak',
            'value'     => null,
            'default'   => true,
        ]);
        factory(App\Setting::class)->create([
            'setting'   => 'discord',
            'value'     => null,
            'default'   => true,
        ]);
        factory(App\Setting::class)->create([
            'setting'   => 'reddit',
            'value'     => null,
            'default'   => true,
        ]);
         factory(App\Setting::class)->create([
            'setting'   => 'facebook',
            'value'     => null,
            'default'   => true,
        ]);
        factory(App\Setting::class)->create([
            'setting'   => 'participant_count_offset',
            'value'     => 0,
            'default'   => true,
        ]);
        factory(App\Setting::class)->create([
            'setting'   => 'lan_count_offset',
            'value'     => 0,
            'default'   => true,
        ]);
        factory(App\Setting::class)->create([
            'setting'   => 'currency',
            'value'     => 'GBP',
            'default'   => true,
        ]);

        factory(App\Setting::class)->create([
            'setting'   => 'about_main',
            'value'     => 'About us Main Here.',
            'default'   => true,
        ]);
        factory(App\Setting::class)->create([
            'setting'   => 'about_short',
            'value'     => 'About us Short Here.',
            'default'   => true,
        ]);
        factory(App\Setting::class)->create([
            'setting'   => 'about_our_aim',
            'value'     => 'About us Our Aim here.',
            'default'   => true,
        ]);
        factory(App\Setting::class)->create([
            'setting'   => 'about_who',
            'value'     => 'About us Whos who here.',
            'default'   => true,
        ]);
    }
}
