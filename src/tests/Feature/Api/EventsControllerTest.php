<?php

namespace Tests\Feature\Api;

use Tests\TestCase;
use App\Models\Event;
use App\Models\EventVenue;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EventsControllerTest extends TestCase
{
    use RefreshDatabase;

    private function makeEventWithVenue(array $overrides = []): Event
    {
        $venue = factory(EventVenue::class)->create();
        return factory(Event::class)->create(array_merge([
            'event_venue_id' => $venue->id,
        ], $overrides));
    }

    public function test_api_events_index_returns_200()
    {
        $response = $this->get('/api/events/');

        $response->assertStatus(200);
    }

    public function test_api_events_index_returns_json_array()
    {
        $this->makeEventWithVenue(['display_name' => 'LAN Ops 56']);

        $response = $this->get('/api/events/');

        $response->assertStatus(200);
        $response->assertJsonStructure([['name', 'start', 'end', 'slug']]);
    }

    public function test_api_events_index_includes_event_name()
    {
        $this->makeEventWithVenue(['display_name' => 'LAN Ops 56']);

        $response = $this->get('/api/events/');

        $response->assertStatus(200);
        $response->assertJsonFragment(['name' => 'LAN Ops 56']);
    }

    public function test_api_events_index_returns_empty_array_when_no_events()
    {
        $response = $this->get('/api/events/');

        $response->assertStatus(200);
        $response->assertExactJson([]);
    }

    public function test_api_event_show_by_slug_returns_200()
    {
        $event = $this->makeEventWithVenue();

        $response = $this->get('/api/events/' . $event->slug);

        $response->assertStatus(200);
        $response->assertJsonFragment(['slug' => $event->slug]);
    }

    public function test_api_event_show_by_id_returns_200()
    {
        $event = $this->makeEventWithVenue();

        $response = $this->get('/api/events/' . $event->id);

        $response->assertStatus(200);
    }

    public function test_api_event_show_returns_address_fields()
    {
        $venue = factory(EventVenue::class)->create([
            'address_city'     => 'Manchester',
            'address_postcode' => 'M1 1AA',
        ]);
        $event = factory(Event::class)->create(['event_venue_id' => $venue->id]);

        $response = $this->get('/api/events/' . $event->slug);

        $response->assertStatus(200);
        $response->assertJsonPath('address.city', 'Manchester');
        $response->assertJsonPath('address.postcode', 'M1 1AA');
    }

    public function test_api_event_show_returns_404_for_unknown_slug()
    {
        $response = $this->get('/api/events/does-not-exist');

        $response->assertStatus(404);
    }

    public function test_api_upcoming_events_returns_200()
    {
        $response = $this->get('/api/events/upcoming');

        $response->assertStatus(200);
    }

    public function test_api_upcoming_events_includes_future_events()
    {
        $this->makeEventWithVenue([
            'display_name' => 'Future LAN',
            'start'        => now()->addMonths(2),
            'end'          => now()->addMonths(2)->addDays(2),
        ]);

        $response = $this->get('/api/events/upcoming');

        $response->assertStatus(200);
        $response->assertJsonFragment(['name' => 'Future LAN']);
    }

    public function test_api_upcoming_events_excludes_past_events()
    {
        $this->makeEventWithVenue([
            'display_name' => 'Old LAN',
            'start'        => now()->subMonths(2),
            'end'          => now()->subMonths(2)->addDays(2),
        ]);

        $response = $this->get('/api/events/upcoming');

        $response->assertStatus(200);
        $response->assertJsonMissing(['name' => 'Old LAN']);
    }

    public function test_api_next_event_returns_200_when_event_exists()
    {
        $this->makeEventWithVenue([
            'start' => now()->addMonths(1),
            'end'   => now()->addMonths(1)->addDays(2),
        ]);

        $response = $this->get('/api/events/next');

        $response->assertStatus(200);
    }

    public function test_api_next_event_returns_404_when_no_upcoming_events()
    {
        $response = $this->get('/api/events/next');

        $response->assertStatus(404);
    }

    public function test_api_event_response_structure()
    {
        $event = $this->makeEventWithVenue();

        $response = $this->get('/api/events/' . $event->slug);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'name',
            'start',
            'end',
            'slug',
            'description' => ['short', 'long'],
            'address' => ['line_1', 'line_2', 'street', 'city', 'postcode', 'country'],
            'url' => ['base', 'tickets', 'participants', 'timetables'],
            'participants',
            'tickets',
        ]);
    }
}
