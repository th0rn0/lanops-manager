<?php

use Illuminate\Database\Seeder;

use Faker\Factory as Faker;

class DatabaseSeeder extends Seeder
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
        \DB::table('events')->delete();
        \DB::table('event_tickets')->delete();
        \DB::table('event_timetables')->delete();
        \DB::table('event_tournaments')->delete();
        \DB::table('event_venues')->delete();
        \DB::table('event_information')->delete();
        \DB::table('games')->delete();

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

        $this->call([
            SettingsTableSeeder::class,
        ]);

        factory(App\Game::class)->create([
            'name'          => 'Quake',
            'description'   => 'Best game ever',
            'version'       => 'latest',
        ]);
    }
}
