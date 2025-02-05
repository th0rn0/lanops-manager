<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use Faker\Factory as Faker;

class EventsSeeder extends Seeder
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
        \DB::table('event_venues')->delete();
        \DB::table('event_information')->delete();

        ## Venue
        $venue = factory(\App\Models\EventVenue::class)->create();

        ## Events
        factory(\App\Models\Event::class)->create([
            'event_venue_id'    => $venue->id,
            'status'            => 'PUBLISHED',
            'capacity'          => 30,
            'type'              => 'LAN',
            'desc_short'        => "Some Awesome LAN Event",
            'display_name'      => 'Another Awesome LAN Event',
            'nice_name'         => strtolower(str_replace(' ', '-', 'Another Awesome LAN Event')),
            'slug'              => strtolower(str_replace(' ', '-', 'Another Awesome LAN Event')),
        ])->each(
            function ($event) {
                factory(\App\Models\EventTicket::class)->create([
                    'event_id' => $event->id,
                ]);
                factory(\App\Models\EventTimetable::class)->create([
                    'event_id' => $event->id,
                ]);
                factory(\App\Models\EventInformation::class, 5)->create([
                    'event_id' => $event->id,
                ]);
                factory(\App\Models\EventSeatingPlan::class)->create([
                    'event_id' => $event->id,
                ]);
            }
        );

        factory(\App\Models\Event::class)->create([
            'event_venue_id'    => $venue->id,
            'status'            => 'PUBLISHED',
            'capacity'          => 30,
            'type'              => 'TABLETOP',
            'desc_short'        => "Some Awesome TABLETOP Event",
            'display_name'      => 'Another Awesome Tabletop Event',
            'nice_name'         => strtolower(str_replace(' ', '-', 'Another Awesome Tabletop Event')),
            'slug'              => strtolower(str_replace(' ', '-', 'Another Awesome Tabletop Event')),
        ])->each(
            function ($event) {
                factory(\App\Models\EventTicket::class)->create([
                    'event_id' => $event->id,
                ]);
                factory(\App\Models\EventTimetable::class)->create([
                    'event_id' => $event->id,
                ]);
                factory(\App\Models\EventInformation::class, 5)->create([
                    'event_id' => $event->id,
                ]);
                factory(\App\Models\EventSeatingPlan::class)->create([
                    'event_id' => $event->id,
                ]);
            }
        );
    }
}
