<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Event;
use App\Models\EventTicket;
use App\Models\EventSeatingPlan;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EventTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_type_array_returns_lan_and_tabletop()
    {
        $types = Event::getTypeArray();

        $this->assertArrayHasKey('LAN', $types);
        $this->assertArrayHasKey('TABLETOP', $types);
        $this->assertEquals('LAN', $types['LAN']);
        $this->assertEquals('TABLETOP', $types['TABLETOP']);
    }

    public function test_type_constants_match_type_array()
    {
        $this->assertEquals('LAN', Event::$typeLan);
        $this->assertEquals('TABLETOP', Event::$typeTabletop);
        $this->assertArrayHasKey(Event::$typeLan, Event::getTypeArray());
        $this->assertArrayHasKey(Event::$typeTabletop, Event::getTypeArray());
    }

    public function test_get_cheapest_ticket_returns_lowest_price()
    {
        $event = factory(Event::class)->create();
        factory(EventTicket::class)->create(['event_id' => $event->id, 'price' => 30]);
        factory(EventTicket::class)->create(['event_id' => $event->id, 'price' => 20]);
        factory(EventTicket::class)->create(['event_id' => $event->id, 'price' => 50]);

        $event->load('tickets');

        $this->assertEquals(20, $event->getCheapestTicket());
    }

    public function test_get_cheapest_ticket_with_single_ticket()
    {
        $event = factory(Event::class)->create();
        factory(EventTicket::class)->create(['event_id' => $event->id, 'price' => 25]);

        $event->load('tickets');

        $this->assertEquals(25, $event->getCheapestTicket());
    }

    public function test_get_cheapest_ticket_with_no_tickets_returns_null()
    {
        $event = factory(Event::class)->create();
        $event->load('tickets');

        $this->assertNull($event->getCheapestTicket());
    }

    public function test_get_seating_capacity_with_no_plans_returns_zero()
    {
        $event = factory(Event::class)->create();
        $event->load('seatingPlans');

        $this->assertEquals(0, $event->getSeatingCapacity());
    }

    public function test_get_seated_count_with_no_plans_returns_zero()
    {
        $event = factory(Event::class)->create();
        $event->load('seatingPlans');

        $this->assertEquals(0, $event->getSeatedCount());
    }

    public function test_get_timetable_data_count_with_no_timetables_returns_zero()
    {
        $event = factory(Event::class)->create();
        $event->load('timetables');

        $this->assertEquals(0, $event->getTimetableDataCount());
    }

    public function test_get_ticket_sales_count_with_no_participants_returns_zero()
    {
        $event = factory(Event::class)->create();
        $event->load('eventParticipants');

        $this->assertEquals(0, $event->getTicketSalesCount());
    }

    public function test_event_has_lan_type_by_default()
    {
        $event = factory(Event::class)->create();

        $this->assertEquals('LAN', $event->type);
    }

    public function test_event_can_be_tabletop_type()
    {
        $event = factory(Event::class)->create(['type' => Event::$typeTabletop]);

        $this->assertEquals('TABLETOP', $event->type);
    }

    public function test_published_event_is_visible_to_guests()
    {
        factory(Event::class)->create(['status' => 'PUBLISHED']);

        $this->assertEquals(1, Event::count());
    }

    public function test_draft_event_is_hidden_from_guests()
    {
        // Remove global scopes to create a draft, then query as guest
        Event::withoutGlobalScopes()->create([
            'display_name' => 'Draft Event',
            'nice_name'    => 'draft-event',
            'slug'         => 'draft-event',
            'start'        => now()->addMonth(),
            'end'          => now()->addMonth()->addDays(2),
            'status'       => 'DRAFT',
            'type'         => 'LAN',
        ]);

        // Guest query (with global scopes) should return 0
        $this->assertEquals(0, Event::count());
    }

    public function test_route_key_is_slug()
    {
        $event = factory(Event::class)->make();

        $this->assertEquals('slug', $event->getRouteKeyName());
    }
}
