<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\EventVenue;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EventVenueTest extends TestCase
{
    use RefreshDatabase;

    public function test_venue_has_display_name()
    {
        $venue = factory(EventVenue::class)->create(['display_name' => 'The Arena']);

        $this->assertEquals('The Arena', $venue->display_name);
    }

    public function test_venue_stores_full_address_fields()
    {
        $venue = factory(EventVenue::class)->create([
            'address_1'        => 'Unit 4',
            'address_street'   => 'Main Street',
            'address_city'     => 'Manchester',
            'address_postcode' => 'M1 1AA',
            'address_country'  => 'United Kingdom',
        ]);

        $this->assertEquals('Unit 4', $venue->address_1);
        $this->assertEquals('Main Street', $venue->address_street);
        $this->assertEquals('Manchester', $venue->address_city);
        $this->assertEquals('M1 1AA', $venue->address_postcode);
        $this->assertEquals('United Kingdom', $venue->address_country);
    }

    public function test_venue_address_fields_are_nullable()
    {
        $venue = factory(EventVenue::class)->create([
            'address_1'        => null,
            'address_2'        => null,
            'address_street'   => null,
            'address_city'     => null,
            'address_postcode' => null,
        ]);

        $this->assertNull($venue->address_1);
        $this->assertNull($venue->address_city);
        $this->assertNull($venue->address_postcode);
    }

    public function test_full_address_parts_can_be_assembled()
    {
        $venue = factory(EventVenue::class)->create([
            'address_1'        => 'Unit 4',
            'address_2'        => null,
            'address_street'   => 'Main Street',
            'address_city'     => 'Manchester',
            'address_postcode' => 'M1 1AA',
        ]);

        $parts = array_filter([
            $venue->address_1,
            $venue->address_2,
            $venue->address_street,
            $venue->address_city,
            $venue->address_postcode,
        ]);

        $this->assertEquals('Unit 4, Main Street, Manchester, M1 1AA', implode(', ', $parts));
    }

    public function test_empty_address_fields_are_excluded_from_assembly()
    {
        $venue = factory(EventVenue::class)->create([
            'address_1'        => null,
            'address_2'        => null,
            'address_street'   => null,
            'address_city'     => 'Manchester',
            'address_postcode' => 'M1 1AA',
        ]);

        $parts = array_filter([
            $venue->address_1,
            $venue->address_2,
            $venue->address_street,
            $venue->address_city,
            $venue->address_postcode,
        ]);

        $this->assertEquals('Manchester, M1 1AA', implode(', ', $parts));
    }

    public function test_route_key_is_slug()
    {
        $venue = factory(EventVenue::class)->make();

        $this->assertEquals('slug', $venue->getRouteKeyName());
    }
}
